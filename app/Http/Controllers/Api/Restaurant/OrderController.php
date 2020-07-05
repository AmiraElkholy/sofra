<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Restaurant;
use App\Models\Client;
use DB;



class OrderController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api-restaurant');
    }



    public function index(Request $request)
    {
        $ownerRestaurant = $request->user(); 
        $records = $ownerRestaurant->orders()->where(function($q) use ($request) {
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
            return responseJson(0, 'No order with this id belongs to this restaurant');
        }

        //set notifications to read
        $order->notifications()->where('order_id', '=', $order->id)->update(['is_seen' => true]);


        return responseJson(1, 'success', $order->load('products'));

    }



    public function rejectOrder(Request $request, $order_id) {
    	
    	$order = $request->user()->orders()->find($order_id);

    	if(!$order) {
    		return responseJson(0, 'No order with this id belongs to this restaurant');
    	}

        $validator = validator()->make($request->all(), [
            'reason_for_rejection' => 'required|min:8'
        ]);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

    	$client = Client::find($order->client_id);

    	if($order->state == 'pending') {

            try {
                DB::beginTransaction();

                $order->update(['state' => 'rejected', 'reason_for_rejection' => $request->reason_for_rejection]);

                /** ** ** Send Notifications To User ** ** **/
                $notification = $client->notifications()->create([
                    'title' => 'تم رفض الطلب',
                    'content' => 'تم رفض طلب الطعام رقم '.$order_id.' من المطعم '.$request->user()->name.' سبب الرفض: '$request->reason_for_rejection,
                    'order_id' => $order->id,
                ]);

                
                $tokens = $client->tokens->where('notification_token', '!=', '')->pluck('notification_token')->toArray();
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

                return responseJson(1, 'order was rejected successfully.');


            } catch(\Exception $e) {

                DB::rollBack();  
            }   
    	}

    	return responseJson(0, 'can not rejecte order, try again');
    }





    public function confirmDelivery(Request $request, $order_id) {

    	$order = $request->user()->orders()->find($order_id);

    	if(!$order) {
    		return responseJson(0, 'No order with this id belongs to this restaurant');
    	}

    	$client = Client::find($order->client_id);

    	if($order->state == 'accepted') {

            try {
                DB::beginTransaction();

                $order->update(['state' => 'delivered']);


                /** ** ** Send Notifications To User ** ** **/
                $notification = $client->notifications()->create([
                    'title' => 'تم تأكيد استلام الطلب',
                    'content' => 'تم تأكيد استلام الطلب رقم '.$order_id.' من المطعم '.$request->user()->name,
                    'order_id' => $order->id,
                ]);

                
                $tokens = $client->tokens()->where('notification_token', '!=', '')->pluck('notification_token')->toArray();
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
                // dd($e->getMessage());

                DB::rollBack();
            }        
    	}

    	return responseJson(0, 'can not confirm delivery');
    }





}
