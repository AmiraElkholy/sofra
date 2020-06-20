<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Client;



class ClientAuthController extends Controller
{

	public function __construct() {
		$this->middleware('guest:api-client');
	}
  
	public function login(Request $request) {

		$rules = [
			'email' => 'required|email',
			'password' => 'required'
		];

		$validator = validator()->make($request->all(), $rules);

		if($validator->fails()) {
			return responseJson(0, $validator->errors()->first(), $validator->errors());
		}

		$client = Client::where('email', '=', $request->email)->first();

		if($client) {
			if(Hash::check($request->password, $client->password)) {
				return responseJson(1, 'تم تسجيل الدخول بنجاح', [
					'api_token' =>  $client->api_token,
					'client'    =>  $client
				]);
			}
		}

    	return responseJson(0, 'خطأ ببيانات تسجيل الدخول'); 
	}

}
