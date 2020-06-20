<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function() {
	
	//Unautherized routes 
	Route::get('cities', 'MainController@cities');
	Route::get('districts', 'MainController@districts');
	Route::get('restaurants', 'MainController@restaurants');
	Route::get('reviews', 'MainController@reviews');
	Route::get('categories', 'MainController@categories');
	Route::get('products', 'MainController@products');
	Route::get('offers', 'MainController@offers');
	Route::get('restaurant-info', 'MainController@restaurantInfo');

	Route::get('about', 'MainController@about');





	//Autherization routes
	Route::post('client/login', 'ClientAuthController@login');





	//Shared routes between clients and restaurants
	Route::post('register-notification-token', 'SharedAuthController@registerNotificationToken');
	Route::post('remove-notification-token', 'SharedAuthController@removeNotificationToken');
	Route::post('test-notifications', 'SharedAuthController@testNotifications');
	

	Route::get('notifications', 'SharedController@notifications');
	Route::post('contact-us', 'SharedController@contactUs');





	Route::group(['middleware' => 'auth:api-client'], function() {
		
		Route::post('new-review', 'MainController@newReview');
	



	});


	Route::group(['middleware' => 'auth:api-restaurant'], function() {
		
		
	



	});




});
