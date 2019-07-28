<?php

use Illuminate\Http\Request;

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

Route::group(["prefix"=>"user",
"namespace"=>"User",
"middleware"=>["CORS"]
],function(){
    Route::post('register', 'AuthController@register');    
    Route::post('login', 'AuthController@login'); 
    Route::post('contact/add','ContactController@addContact');
    Route::get('contact/get-all/{pagination?}','ContactController@getPaginatedData');
    Route::get('contact/search/{search}/{pagination?}','ContactController@searchData');
    Route::get('contact/get-single/{id}','ContactController@GetSingleData');
    Route::post('contact/update/{id}','ContactController@editSingleData');
   // Route::post('',' ContactController@deleteContent');
   Route::post('contact/delete/{id}','ContactController@deleteContent');

});

