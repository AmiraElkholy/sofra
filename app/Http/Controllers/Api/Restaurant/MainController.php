<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    
	public function notifications(Request $request) {
        $notifications = $request->user()->notifications()->paginate(20);
        return responseJson(1, 'success', $notifications);
    }

}
