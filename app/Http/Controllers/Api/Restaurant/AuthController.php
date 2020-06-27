<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;



use App\Models\Restaurant;




class AuthController extends Controller
{
    
	public function __construct() {
		$this->middleware('guest:api-client');
	}


	public function register(Request $request) {
    	//Y-n-j date format example:  1991-1-1
    	//Y-m-d date format example:  1991-01-01
    	$rules = [
    		'name' 		     =>  'required|min:3|max:100',
    		'email' 		 =>  'required|email|unique:restaurants',
    		'delivery_time'  =>  'required|integer',
    		'district_id' 	 =>  'required|integer|exists:districts,id',
    		'password' 		 =>  'required|confirmed|min:8',
            'minimum_charge' =>  'required|numeric',
            'delivery_fees'  =>  'required|numeric',
            'phone'          =>  'required|unique:restaurants|regex:/(01)[0-9]{9}/|size:11',
            'whatsapp'       =>  'required|unique:restaurants|regex:/(01)[0-9]{9}/|size:11',
            'image'          =>  'required',
            'is_open'        =>  'required|boolean',
    	];

    	$validator = validator()->make($request->all(), $rules);


    	if($validator->fails()) {
    		return responseJson(0, $validator->errors()->first(), $validator->errors());
    	}

    	//$request->merge(['password' => bcrypt($request->password)]);
    	$restaurant = Restaurant::create($request->all());
    	$restaurant->api_token = Str::random(60);
    	$restaurant->save();

    	return responseJson(1, 'تم التسجيل بنجاح', [
    		'api_token' => $restaurant->api_token,
    		'restaurant'    => $restaurant
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

		$restaurant = Restaurant::where('email', '=', $request->email)->first();

		if($restaurant) {
			if(Hash::check($request->password, $restaurant->password)) {
				return responseJson(1, 'تم تسجيل الدخول بنجاح', [
					'api_token' =>  $restaurant->api_token,
					'restaurant'    =>  $restaurant
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


        $restaurant = Restaurant::where('email', $request->email)->first();
        

        if($restaurant) {

            $code = rand(1111,9999);

            $update = $restaurant->update(['pin_code' => $code]);

            if($update) {

                //send code via sms
                /*   --- example ---
                    smsMisr($request->user()->phone, "Your reset code is: ".$code);
                    //TODO: check for success or fail ....
                */
                
                //send code via email
                Mail::to($restaurant->email)
                    ->bcc('amiraelkholy16@gmail.com')
                    ->send(new PasswordReset($restaurant));


                return responseJson(1, 'افحص إيميلك من أجل الكود', [
                                            'pin_code_for_test' => $code,
                                            'mail_fails' => mail::failures(),
                                            'restaurant email' => $restaurant->email
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


        $restaurant = Restaurant::where('pin_code', $request->pin_code)->where('pin_code','!=', 0)->first();

        if($restaurant) {

            $update = $restaurant->update(['password' => $request->new_password, 'pin_code' => null]);

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
            'email'         =>  'email|unique:restaurants,email,'.$loginUser->id,  
            'delivery_time'  =>  'integer',                
            'district_id'   =>  'integer|exists:districts,id',                          
            'password'      =>  'confirmed',
            'minimum_charge'=> 'numeric',
            'delivery_fees' => 'numeric',
            'phone'         =>  'regex:/(01)[0-9]{9}/|size:11|unique:restaurants,phone'.$loginUser->id,
            'whatsapp' => 'regex:/(01)[0-9]{9}/|size:11|unique:restaurants,whatsapp'.$loginUser->id,
            'is_open' => 'boolean',            
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $loginUser->update($request->all());

        return responseJson(1, 'success', $loginUser);
    }



}
