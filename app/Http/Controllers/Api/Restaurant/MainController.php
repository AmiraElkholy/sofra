<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AppSetting;

class MainController extends Controller
{
    
	public function __construct() {
        $this->middleware('auth:api-restaurant');
    }


	public function notifications(Request $request) {
        $notifications = $request->user()->notifications()->paginate(20);
        return responseJson(1, 'success', $notifications);
    }


    public function restaurantCommissions(Request $request) {
    	
    	$page_text = AppSetting::pluck('commissions_page_text')->first();

    	$restaurantSales = $request->user()->orders->where('state', 'delivered')->sum('total');

    	$appCommissions = $request->user()->orders->where('state', 'delivered')->sum('commission');

        $restaurantNetSales = $request->user()->orders->where('state', 'delivered')->sum('net');

    	$downpayments = $request->user()->downpayments->where('state', 'delivered')->sum('amount');

    	$theRemainingPayment = $appCommissions - $downpayments;

    	$payment_account_details = AppSetting::pluck('payment_account_details')->first();

    	$data = [
    		'page_text' => $page_text,
            'restaurantSales' => $restaurantSales,
            'appCommissions' => $appCommissions,
            'restaurantNetSales' => $restaurantNetSales,
            'downpayments' => $downpayments,
            'theRemainingPayment' => $theRemainingPayment,
            'payment_account_details' => $payment_account_details,
    	];

    	return responseJson(1, 'success', $data);
    }

}
