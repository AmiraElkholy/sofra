<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Token;


class SharedAuthController extends Controller
{
	

	public function __construct() {
		$this->middleware('auth:api-client,api-restaurant', ['except' => ['removeNotificationToken']]);
	}    



    public function registerNotificationToken(Request $request) {

        $rules = [
            'api_token' => 'required',
            'notification_token'     => 'required',
            'platform'  => 'required|in:android,ios'
        ];

        $validator = validator()->make($request->all(), $rules);

       if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        Token::where('notification_token', $request->notification_token)->delete();

        $request->user()->tokens()->create($request->all());

        return responseJson(1,'تم إعداد الجهاز لاستقبال الإشعارات بنجاح');

    }


    public function removeNotificationToken(Request $request) {

        $rules = [
            'notification_token' => 'required' 
        ];

        $validator = validator()->make($request->all(), $rules);

       if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        Token::where('notification_token', $request->notification_token)->delete();

        return responseJson(1,'تم الحذف بنجاح');

    }


    public function testNotifications(Request $request) {
        
         if($request->notification_tokens->count()) {
            $tokens = $request->notification_tokens;
            $title = $request->title;
            $body = $request->content;
            $data = [
                'data' => 'test notification'
            ];
            $send = notifyByFirebase($title, $body, $tokens, $data);
            //dd($send);
            // info("firebase result: ".$send);
            // info("data: ".json_encode(data));
        } 
        return responseJson(1, 'data sent', $send);        
    }
}
