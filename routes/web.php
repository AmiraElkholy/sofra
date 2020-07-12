<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['verify' => true]);


Route::get('/', 'HomeController@index')->middleware(['auth:web', 'verified']);
Route::get('/home', 'HomeController@index')->middleware(['auth:web', 'verified']);
Route::get('/dashboard', 'HomeController@index')->middleware(['auth:web', 'verified']);
Route::get('/admin', 'HomeController@index')->middleware(['auth:web', 'verified']);


Route::group(['middleware' => ['auth:web'], 'prefix' => 'admin'], function() { 
	Route::resource('cities', 'CityController');
	Route::resource('districts', 'DistrictController');



});





Route::resource('categories', 'CategoryController');