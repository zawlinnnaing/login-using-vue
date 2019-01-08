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
Route::get('refresh', 'AuthApiController@refresh');
Route::post('send_verification_email','AuthApiController@sendVerificationEmail');


Route::middleware('auth:api')->group(function () {
    Route::apiResource('user', 'AuthApiController');
    Route::get('verify-email', 'AuthApiController@verifyEmail');
    Route::get('home', function () {
        return response()->json(['message' => 'Resource accessed successfully'],200     );
    });
    Route::post('logout', 'AuthApiController@logout');
    Route::post('/profile/change_password','AuthApiController@changePassword');

    Route::apiResource('/{user}/post','PostApiController');

    Route::apiResource('/post','v2\PostApiController');

    Route::apiResource('/{post}/comment','CommentApiController');
});



