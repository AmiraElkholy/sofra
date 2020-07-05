<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Restaurant;
use App\Models\Product;
use App\Models\AppSetting;


use DB;
use Exception;

class OrderController extends Controller
{
    
	public function __construct() {
        $this->middleware('auth:api-client');
    }


	public function newOrder(Request $request) {

		$rules = [
			'restaurant_id' => 'required|integer|exists:restaurants,id',
			'products.*.product_id' => 'required|integer|exists:products,id',
			'products.*.quantity' => 'required|integer',
			'delivery_address' => 'required|min:10',
			'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'phone'          =>  'required|regex:/(01)[0-9]{9}/|size:11',
		];

		$validator = validator()->make($request->all(), $rules);

    	if($validator->fails()) {
    		return responseJson(0, $validator->errors()->first(), $validator->errors());
    	}


    	$restaurant = Restaurant::find($request->restaurant_id);


    	//verify if restaurant is closed
    	if(!$restaurant->is_open) {
    		return responseJson(0, 'عذرا المطعم غير متاح في الوقت الحالي');
    	}


    	try {

    		DB::beginTransaction();

    		$order = $request->user()->orders()->create([
    			'restaurant_id' => $request->restaurant_id,
    			'delivery_address' => $request->delivery_address,
    			'payment_method_id' => $request->payment_method_id,
    			'notes' => $request->notes,
    			'delivery_fees' => $restaurant->delivery_fees,
    			'phone' => $request->phone,
    		]);


    		//calculate the cost of the order and check against the minimum charge
    		$cost = 0;
    		$delivery_fees = $restaurant->delivery_fees;
    		$commissions_rate = AppSetting::first()->commissions_rate;


    		foreach ($request->products as $product_item) {
    			
    			$product = Product::find($product_item['product_id']);

    			$productWithPivotAttributes = [
    				$product_item['product_id'] => [
    					'quantity' => $product_item['quantity'],
    					'invoice_price'	   => $product->price,
    					'special_notes'	   => isset($product_item['note']) ? $product_item['note'] : ''
    				]
    			];


    			$order->products()->attach($productWithPivotAttributes);    

    			$cost += $product_item['quantity'] * $product->price;		

    		}

    		// check to see if minimum charge is achieved
    		if($cost<$restaurant->minimum_charge) {
    			throw new Exception("minimum charge");
    		}


    		//minimum charge is achieved
    		$total = $cost + $delivery_fees;
    		$commission = $cost * ($commissions_rate / 100);
    		$net = $total - $commission;

    		$update = $order->update([
    			'sub_total' => $cost,
    			'total' => $total,
    			'commission' => $commission,
    			'net' => $net,
    		]);


    		/** ** ** Send Notifications To Restaurant ** ** **/
    		$notification = $restaurant->notifications()->create([
    			'title' => 'لديك طلب طعام جديد',
    			'content' => 'لديك طلب جديد من العميل '.$request->user()->name,
    			'order_id' => $order->id,
    		]);

            
           	$tokens = $restaurant->tokens->where('notification_token', '!=', '')->pluck('notification_token')->toArray();
            $title = $notification->title;
            $body = $notification->content;
            $data = [
                'data' => $notification
            ];
            $send = notifyByFirebase($title, $body, $tokens, $data);
            //dd($send);
            // info("firebase result: ".$send);
            // info("data: ".json_encode(data));
	      

    		DB::commit();

    		$data = $order->fresh()->load('products');

    		return responseJson(1, 'success', $data);

    	} catch (\Exception $e) {

      		DB::rollBack();

      		$errorMsg = $e->getMessage();

    		if($errorMsg == 'minimum charge') {
    			return responseJson(0, "Sorry minimum charge not reached for this restaurant. This restaurant can not deliver for under ".$restaurant->minimum_charge);
    		}

    		return responseJson(0, 'Sorry somthing went wrong. Order was not made, please try again,', );
    	}

	}


	public function index(Request $request)
    {
        $ownerUser = $request->user(); 
        $records = $ownerUser->orders()->where(function($q) use ($request) {
        	if($request->input('order_type') == 'new') {
        		$q->where('state', '=', 'pending');
        	}
        	else if($request->input('order_type') == 'current') {
        		$q->where('state', '=', 'accepted');
        	}
        	else if($request->input('order_type') == 'old') {
        		$q->where(['state' => 'delivered', 'state' => 'rejected', 'state' => 'declined']);
        	}
        })->with('products')->paginate(20);
        return responseJson(1, 'success', $records);
    }


    public function show(Request $request, $order_id) {
        $order = $request->user()->orders()->find($order_id);

        if(!$order) {
            return responseJson(0, 'No order with this id belongs to this user');
        }

        return responseJson(1, 'success', $order->load('products'));
    }



    public function cancelOrder(Request $request, $order_id) {
    	
    	$order = $request->user()->orders()->find($order_id);

    	if(!$order) {
    		return responseJson(0, 'No order with this id belongs to this user');
    	}

    	$restaurant = Restaurant::find($order->restaurant_id);

    	if($order->state == 'pending') {

            try {
                DB::beginTransaction();

                $order->update(['state' => 'declined']);

                /** ** ** Send Notifications To Restaurant ** ** **/
                $notification = $restaurant->notifications()->create([
                    'title' => 'تم إلغاء طلب الطعام',
                    'content' => 'تم إلغاء طلب الطعام رقم '.$order_id.' من العميل '.$request->user()->name,
                    'order_id' => $order->id,
                ]);

                
                $tokens = $restaurant->tokens->where('notification_token', '!=', '')->pluck('notification_token')->toArray();
                $title = $notification->title;
                $body = $notification->content;
                $data = [
                    'data' => $notification
                ];
                // dd($tokens, "<Br>", $title, $body, $data);
                $send = notifyByFirebase($title, $body, $tokens, $data);
                //dd($send);
                // info("firebase result: ".$send);
                // info("data: ".json_encode(data));

                DB::commit();

                return responseJson(1, 'order canceled successfully.');


            } catch(\Exception $e) {

                DB::rollBack();  
            }   
    	}

    	return responseJson(0, 'can not cancel order, order was accepted by restaurant and is being prepared!');
    }


    public function confirmDelivery(Request $request, $order_id) {

    	$order = $request->user()->orders()->find($order_id);

    	if(!$order) {
    		return responseJson(0, 'No order with this id belongs to this user');
    	}

    	$restaurant = Restaurant::find($order->restaurant_id);

    	if($order->state == 'accepted') {

            try {
                DB::beginTransaction();

                $order->update(['state' => 'delivered']);


                /** ** ** Send Notifications To Restaurant ** ** **/
                $notification = $restaurant->notifications()->create([
                    'title' => 'تم تأكيد استلام الطلب',
                    'content' => 'تم تأكيد استلام الطلب رقم '.$order_id.' من العميل '.$request->user()->name,
                    'order_id' => $order->id,
                ]);

                
                $tokens = $restaurant->tokens->where('notification_token', '!=', '')->pluck('notification_token')->toArray();
                $title = $notification->title;
                $body = $notification->content;
                $data = [
                    'data' => $notification
                ];
                // dd($tokens, "<Br>", $title, $body, $data);
                $send = notifyByFirebase($title, $body, $tokens, $data);
                //dd($send);
                // info("firebase result: ".$send);
                // info("data: ".json_encode(data));

                DB::commit();

                return responseJson(1, 'success, delivery confirmed', $order->fresh());


            } catch(\Exception $e) {

                DB::rollBack();
            }        
    	}

    	return responseJson(0, 'can not confirm delivery');
    }



    public function declineOrder(Request $request, $order_id) {
    	
        $order = $request->user()->orders()->find($order_id);

    	if(!$order) {
    		return responseJson(0, 'No order with this id belongs to this user');
    	}

    	$restaurant = Restaurant::find($order->restaurant_id);

    	if($order->state == 'accepted') {

    		try {
                DB::beginTransaction();

                $order->update(['state' => 'declined']);

                /** ** ** Send Notifications To Restaurant ** ** **/
                $notification = $restaurant->notifications()->create([
                    'title' => 'تم رفض استلام الطلب',
                    'content' => 'تم رفض استلام الطلب رقم '.$order_id.' من العميل '.$request->user()->name,
                    'order_id' => $order->id,
                ]);

                
                $tokens = $restaurant->tokens->where('notification_token', '!=', '')->pluck('notification_token')->toArray();
                $title = $notification->title;
                $body = $notification->content;
                $data = [
                    'data' => $notification
                ];
                // dd($tokens, "<Br>", $title, $body, $data);
                $send = notifyByFirebase($title, $body, $tokens, $data);
                //dd($send);
                // info("firebase result: ".$send);
                // info("data: ".json_encode(data));

                DB::commit();

                return responseJson(1, 'success, order declined');


            } catch(\Exception $e) {
                
                DB::rollBack();
            }  
    	}

    	return responseJson(0, 'can not decline order');
    }



    public function newReview(Request $request) {

		$rules = [
			'restaurant_id' => 'required|integer|exists:restaurants,id',
			'api_token' => 'required',
			'stars' => 'required|integer|in:1,2,3,4,5',
			'comment' => 'required|min:3'
		];

		$validator = validator()->make($request->all(), $rules);

		if($validator->fails()) {
			return responseJson(0, $validator->errors()->first(), $validator->errors());
		}


		$review = $request->user()->reviews()->create($request->all());
    	

    	return responseJson(1, 'success', $review);
    }




}
