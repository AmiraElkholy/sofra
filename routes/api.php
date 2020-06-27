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
	Route::post('new-review', 'MainController@newReview')->middleware('auth:api-client');

	Route::get('about', 'MainController@about');





	//Auth routes for clients 
	Route::post('client/register', 'ClientAuthController@register');
	Route::post('client/login', 'ClientAuthController@login');
	Route::post('client/reset-password', 'ClientAuthController@resetPassword');
	Route::post('client/new-password', 'ClientAuthController@newPassword');
	

	//Auth routes for resturants
	Route::post('restaurant/register', 'RestaurantAuthController@register');
	Route::post('restaurant/login', 'RestaurantAuthController@login');
	Route::post('restaurant/reset-password', 'RestaurantAuthController@resetPassword');
	Route::post('restaurant/new-password', 'RestaurantAuthController@newPassword');





	//Shared Auth routes between clients and restaurants
	Route::post('register-notification-token', 'SharedAuthController@registerNotificationToken');
	Route::post('remove-notification-token', 'SharedAuthController@removeNotificationToken');
	Route::post('test-notifications', 'SharedAuthController@testNotifications');
	//Shared routes
	Route::post('contact-us', 'SharedController@contactUs');





	Route::group(['middleware' => 'auth:api-client', 'namespace' => 'Client'], function() {
		
		Route::get('client/profile', 'AuthController@profile');
		Route::post('client/profile', 'AuthController@profile');
		Route::get('notifications', 'MainController@notifications');
		




	});


	Route::group(['middleware' => 'auth:api-restaurant', 'namespace' => 'Restaurant'], function() {
		
		Route::get('restaurant/profile', 'AuthController@profile');
		Route::post('restaurant/profile', 'AuthController@profile');
		Route::get('notifications', 'MainController@notifications');

	



	});




});
