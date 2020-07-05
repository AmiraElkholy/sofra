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
	//Non authorized routes 
	Route::get('cities', 'MainController@cities');
	Route::get('districts', 'MainController@districts');
	Route::get('restaurants', 'MainController@restaurants');
	Route::get('reviews', 'MainController@reviews');
	Route::get('categories', 'MainController@categories');
	Route::get('product', 'MainController@product');
	Route::get('products', 'MainController@products');
	Route::get('offers', 'MainController@offers');
	Route::get('restaurant-info', 'MainController@restaurantInfo');
	Route::get('app-settings', 'MainController@appSettings');
	Route::get('app-settings', 'MainController@appSettings');
	Route::get('about', 'MainController@about');



	
	//Shared Auth routes between clients and restaurants
	Route::post('register-notification-token', 'SharedAuthController@registerNotificationToken');
	Route::post('remove-notification-token', 'SharedAuthController@removeNotificationToken');
	Route::post('test-notifications', 'SharedAuthController@testNotifications');

	//Shared routes
	Route::post('contact-us', 'SharedController@contactUs');











	Route::group(['namespace' => 'Client'], function() {	

		Route::post('client/register', 'AuthController@register');
		Route::post('client/login', 'AuthController@login');
		Route::post('client/reset-password', 'AuthController@resetPassword');
		Route::post('client/new-password', 'AuthController@newPassword');
		


		Route::group(['middleware' => 'auth:api-client'], function() {

			Route::get('client/profile', 'AuthController@profile');
			Route::post('client/profile', 'AuthController@profile');
			Route::get('notifications', 'MainController@notifications');
			Route::post('new-order', 'OrderController@newOrder');
			Route::get('client/orders', 'OrderController@index');
			Route::get('client/orders/{order_id}', 'OrderController@show');
			Route::delete('client/orders/{order_id}/cancel', 'OrderController@cancelOrder');
			Route::get('client/orders/{order_id}/confirm-delivery', 'OrderController@confirmDelivery');
			Route::get('client/orders/{order_id}/decline-order', 'OrderController@declineOrder');
			Route::post('new-review', 'OrderController@newReview');




		});
		
		



	});


	Route::group(['namespace' => 'Restaurant'], function() {

		Route::post('restaurant/register', 'AuthController@register');
		Route::post('restaurant/login', 'AuthController@login');
		Route::post('restaurant/reset-password', 'AuthController@resetPassword');
		Route::post('restaurant/new-password', 'AuthController@newPassword');




		Route::group(['middleware' => 'auth:api-restaurant'], function() {

			Route::get('restaurant/profile', 'AuthController@profile');
			Route::post('restaurant/profile', 'AuthController@profile');
			Route::get('notifications', 'MainController@notifications');
			//Resources
			Route::resource('categories', 'CategoryController');
			Route::resource('categories/{category_id}/products', 'ProductController');
			Route::resource('offers', 'OfferController');
			Route::get('restaurant/orders', 'OrderController@index');
			Route::get('restaurant/orders/{order_id}', 'OrderController@show');
			Route::get('restaurant/orders/{order_id}/reject-order', 'OrderController@rejectOrder');
			Route::get('restaurant/orders/{order_id}/confirm-delivery', 'OrderController@confirmDelivery');
			//commissions
			Route::get('restaurant-commissions', 'MainController@restaurantCommissions');



		});


	

	});




});
