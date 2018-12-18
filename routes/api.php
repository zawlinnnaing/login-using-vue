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

Route::post('login', 'AuthApiController@login');
Route::post('register', 'AuthApiController@register');
Route::get('user', 'AuthApiController@user');
Route::get('refresh', 'AuthApiController@refresh');


Route::middleware('auth:api')->group(function () {
    Route::get('verify-email', 'AuthApiController@verifyEmail');
    Route::get('home', function () {
        return response()->json(['message' => 'Resource accessed successfully']);
    });
    Route::post('logout', 'AuthApiController@logout');

});



