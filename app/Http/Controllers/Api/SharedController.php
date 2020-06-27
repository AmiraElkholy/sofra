<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



use App\Models\Contact;


class SharedController extends Controller
{
    

	public function __construct() {
		$this->middleware('auth:api-client,api-restaurant');
	}



    public function contactUs(Request $request) {

    	$rules = [
            'fullname' => 'required|min:3',
    		'email'    => 'required|email',
            'phone'    => 'required|regex:/(01)[0-9]{9}/|size:11' ,
            'message'  => 'required|min:10',
            'type'     => 'required|in:complaint,suggestion,inquiry',
            'api_token' => 'required'
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $contact = $request->user()->contacts()->create($request->all());

        return responseJson(1, 'تم إرسال رسالتك بنجاح', $contact);
    }


}
