<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    
	public function notifications(Request $request) {
        $notifications = $request->user()->notifications()->paginate(20);
        $notifications->update('is_seen', 1);
        return responseJson(1, 'success', $notifications);
    }



}
