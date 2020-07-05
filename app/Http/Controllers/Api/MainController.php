<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Validation\Rule;
use Carbon\Carbon;

//Eloquent Model Classes
use App\Models\Client;
use App\Models\City;
use App\Models\District;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\Category;
use App\Models\Product;
use App\Models\Offer;
use App\Models\AppSetting;




class MainController extends Controller
{
    
    public function cities() {

    	$cities = City::all();

    	if(!$cities) {
    		return responseJson(0, 'failed', $cities);
    	}

    	return responseJson(1, 'success', $cities);
    }


    public function districts(Request $request) {

    	$districts = District::where(function($query) use($request) {

    		if($request->has('city_id')) {
    		
    			$query->where('city_id', '=', $request->city_id);
    		
    		}

    	})->paginate(20);

    	if(!$districts) {
    		return responseJson(0, 'failed', $districts);
    	}

    	return responseJson(1, 'success', $districts);
    }


    public function restaurants(Request $request) {

    	$restaurants = Restaurant::where(function($query) use($request) {

    		if($request->has('city_id')) {
    			$districts_ids = City::find($request->city_id)->districts()->pluck('id')->toArray();
    			$query->whereIN('district_id', $districts_ids);	
    		}

    		if($request->has('name')) { 		
    			$query->where('name', 'LIKE', '%'.$request->name.'%');  		
    		}

    	})->paginate(20);

    	if(!$restaurants) {
    		return responseJson(0, 'failed', $restaurants);
    	}

    	return responseJson(1, 'success', $restaurants);
    }




    public function reviews(Request $request) {

    	$rules = [
			'restaurant_id' => 'required|integer|exists:restaurants,id',
		];

		$validator = validator()->make($request->all(), $rules);

		if($validator->fails()) {
			return responseJson(0, $validator->errors()->first(), $validator->errors());
		}


    	$reviews = Review::where('restaurant_id', '=', $request->restaurant_id)->paginate(20);

    	if(!$reviews) {
    		return responseJson(0, 'failed', $reviews);
    	}

    	return responseJson(1, 'success', $reviews);
    }


    public function categories(Request $request) {

    	$rules = [
			'restaurant_id' => 'required|integer|exists:restaurants,id',
		];

		$validator = validator()->make($request->all(), $rules);

		if($validator->fails()) {
			return responseJson(0, $validator->errors()->first(), $validator->errors());
		}


    	$categories = Category::where('restaurant_id', '=', $request->restaurant_id)->paginate(20);

    	if(!$categories) {
    		return responseJson(0, 'failed', $categories);
    	}

    	return responseJson(1, 'success', $categories);
    }



    public function products(Request $request) {
    	$id = '';

		if($request->has('restaurant_id')&&!empty($request->restaurant_id)) {
			$id = 'restaurant';
		}

		if($request->has('category_id')&&!empty($request->category_id))  {
			$id = 'category';
		}

		if(($request->has('restaurant_id')&&!empty($request->restaurant_id))&&($request->has('category_id')&&!empty($request->category_id))) {
			$id = 'restaurant&category';
		}


    	switch ($id) {
    		
    		case 'restaurant':
    			if(!Restaurant::find($request->restaurant_id)) {
					return responseJson(0, 'Error: No restaurant found with that id');
				}

				$products = Product::where(function($query) use($request) {

					$categories_ids = Restaurant::find($request->restaurant_id)->categories()->pluck('id')->toArray();
					
					$query->whereIN('category_id', $categories_ids);	

		    	})->with('category')->paginate(20);


    			break;
    		

    		case 'category':
    			if(!Category::find($request->category_id)) {
					return responseJson(0, 'Error: No category found with that id');
				}

				$products = Product::where('category_id', '=', $request->category_id)->with('category')->paginate(20);


    			break;	

    		case 'restaurant&category':
    			if(!Restaurant::find($request->restaurant_id)) {
					return responseJson(0, 'Error: No restaurant found with that id');
				}
				if(!Category::find($request->category_id)) {
					return responseJson(0, 'Error: No category found with that id');
				}

				$products = Product::where(function($query) use($request) {

					$categories_ids = Restaurant::find($request->restaurant_id)->categories()->pluck('id')->toArray();
					
					$query->whereIN('category_id', $categories_ids);	

					if($request->has('category_id')) {
						$query->where('category_id', '=', $request->category_id);
					}

		    	})->with('category')->paginate(20);


    			break;

    		default:
	        	return responseJson(0, 'Error: Please choose a restaurant or a category to show its food items!');
    			break;
    	}


    	if(!$products) {
    		return responseJson(0, 'failed', $products);
    	}

    	return responseJson(1, 'success', $products);
    }


    public function product(Request $request) {
        $rules = [
            'product_id' => 'required|integer|exists:products,id',
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $product = Product::find($request->product_id);

        if(!$product) {
            return responseJson(0, "can't find product with this id");
        }

        return responseJson(1, 'success', $product);
    }



    public function offers(Request $request) {

    	$rules = [
			'restaurant_id' => 'required|integer|exists:restaurants,id',
		];

		$validator = validator()->make($request->all(), $rules);

		if($validator->fails()) {
			return responseJson(0, $validator->errors()->first(), $validator->errors());
		}


    	$offers = Offer::where('restaurant_id', '=', $request->restaurant_id)->paginate(20);

    	if(!$offers) {
    		return responseJson(0, 'failed', $offers);
    	}

    	return responseJson(1, 'success', $offers);
    }



    public function restaurantInfo(Request $request) {

    	$rules = [
			'restaurant_id' => 'required||integer|exists:restaurants,id',
		];

		$validator = validator()->make($request->all(), $rules);

		if($validator->fails()) {
			return responseJson(0, $validator->errors()->first(), $validator->errors());
		}


		$restaurant = Restaurant::select('name', 'is_open', 'district_id', 'minimum_charge', 'delivery_fees')->where('id', '=', $request->restaurant_id)->first()->toArray();


		$restaurant = (object) $restaurant;
		$restaurant->city = District::find($restaurant->district_id)->city->name;
		$restaurant->district = District::find($restaurant->district_id)->name;
		unset($restaurant->district_id);
		unset($restaurant->is_open);
		// dd($restaurant);

		return responseJson(1, 'success', $restaurant);

    }

    public function appSettings() {
        $record = AppSetting::first();
        return responseJson(1, 'success', $record);
    }


    public function about() {
    	$about = AppSetting::pluck('about_us_text')->first();
    	$data = [
    		'about' => $about,
    	];
    	return responseJson(1, 'success', $data);
    }





}
