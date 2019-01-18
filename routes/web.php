<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::get('/mail-test', function () {
    return new \App\Mail\SendVerifyEmailApi('12345');
});

Route::get('/test-url/{string}', function ($string) {
    echo url()->full();
});


// Testing routes
Route::get('/test-user-resource/{id}', function ($id) {
    return new \App\Http\Resources\UserResource(\App\User::find($id));
});


Route::get('/test-broadcast-comment', function () {
    $comment = \App\Comment::find(210)->first();
    $commentResource = new \App\Http\Resources\CommentResource($comment);
    broadcast(new \App\Events\CommentPosted($commentResource));
});