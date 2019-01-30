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
Route::post('send_verification_email', 'AuthApiController@sendVerificationEmail');

Route::get('/posts', 'v2\PostApiController@index');
Route::apiResource('/post', 'v2\PostApiController')->only(['index', 'show']);
Route::resource('/{post}/comment', 'CommentApiController')->only(['index']);

Route::get('/get_user/{id}','UserController@getUser');
Route::get('/get_posts/{userId}','PostApiController@index');

Route::middleware('auth:api')->group(function () {
    Route::get('refresh', 'AuthApiController@refresh');
    Route::apiResource('user', 'UserController');
    Route::get('verify-email', 'AuthApiController@verifyEmail');
    Route::get('home', function () {
        return response()->json(['message' => 'Resource accessed successfully'], 200);
    });
    Route::post('logout', 'AuthApiController@logout');
    Route::post('/profile/change_password', 'AuthApiController@changePassword');
    Route::resource('/{post}/comment', 'CommentApiController')->only(['store', 'update', 'destroy'])
        ->middleware('checkActiveUser');

    Route::apiResource('/{user}/post', 'PostApiController');


});

// Testing api endpoints

Route::post('post_image', function (Request $request) {
    return response()->json($request->input('image'), 200);
});


