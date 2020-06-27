<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;




use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;



use App\Models\Client;



class AuthController extends Controller
{

	public function __construct() {
		$this->middleware('guest:api-client', ['except' => ['profile']]);
	}


	public function register(Request $request) {
    	//Y-n-j date format example:  1991-1-1
    	//Y-m-d date format example:  1991-01-01
    	$rules = [
    		'name' 				  =>  'required|min:3|max:100',
    		'email' 			  =>  'required|email|unique:clients',
    		'phone' 			  =>  'required|unique:clients|regex:/(01)[0-9]{9}/|size:11',
    		'image' 	 		  =>  'required',
    		'district_id' 		  =>  'required|integer|exists:districts,id',
    		'password' 			  =>  'required|confirmed|min:8'
    	];

    	$validator = validator()->make($request->all(), $rules);


    	if($validator->fails()) {
    		return responseJson(0, $validator->errors()->first(), $validator->errors());
    	}

    	//$request->merge(['password' => bcrypt($request->password)]);
    	$client = Client::create($request->all());
    	$client->api_token = Str::random(60);
    	$client->save();

    	return responseJson(1, 'تم التسجيل بنجاح', [
    		'api_token' => $client->api_token,
    		'client'    => $client
    	]);
    	
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




	public function resetPassword(Request $request) {

        $validator = validator()->make($request->all(), [
            'email' => 'required|email'
        ]);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }


        $client = Client::where('email', $request->email)->first();
        

        if($client) {

            $code = rand(1111,9999);

            $update = $client->update(['pin_code' => $code]);

            if($update) {

                //send code via sms
                /*   --- example ---
                    smsMisr($request->user()->phone, "Your reset code is: ".$code);
                    //TODO: check for success or fail ....
                */
                
                //send code via email
                Mail::to($client->email)
                    ->bcc('amiraelkholy16@gmail.com')
                    ->send(new PasswordReset($client));


                return responseJson(1, 'افحص إيميلك من أجل الكود', [
                                            'pin_code_for_test' => $code,
                                            'mail_fails' => mail::failures(),
                                            'client email' => $client->email
                                        ]);
            }
            else {
                return responseJson(0, 'حدث خطأ ، برجاء المحاولة مرة أخرى');
            }
        }
        else {
            return responseJson(0,'لا يوجد أي حساب مرتبط بهذا البريد');
        }
    }




    public function newPassword(Request $request) {

        $rules = [
            'pin_code' => 'required',
            'new_password' => 'required|confirmed'
        ];


        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }


        $client = Client::where('pin_code', $request->pin_code)->where('pin_code','!=', 0)->first();

        if($client) {

            $update = $client->update(['password' => $request->new_password, 'pin_code' => null]);

            if($update) {
                return responseJson(1, 'تم تغيير كلمة السر بنجاح');
            }
            else {
                return responseJson(0, 'حدث خطأ ، حاول مرة أخرى');
            } 

        }

        else {
            return responseJson(0, 'هذا الكود غير صالح');
        }

    }



    public function profile(Request $request) {

        $loginUser = $request->user();

        $rules = [
            'name'          =>  'min:3',
            'email'         =>  'email|unique:clients,email,'.$loginUser->id,                  
            'phone'         =>  'regex:/(01)[0-9]{9}/|size:11|unique:clients,phone'.$loginUser->id,
            'district_id'   =>  'integer|exists:districts,id',                          
            'password'      =>  'confirmed'
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $loginUser->update($request->all());

        return responseJson(1, 'success', $loginUser);
    }




}
