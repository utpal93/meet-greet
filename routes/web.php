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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('user/search', 'HomeController@search_user');

Route::get('send/friend_request/{id}', 'HomeController@save_friend_request');

Route::get('/get_notification_count', 'HomeController@notification_count');
Route::get('/get_notifications', 'HomeController@notifications');

Route::get('user_profile_n/{id}', 'HomeController@user_profile_n');
Route::get('user_profile/{id}', 'HomeController@user_profile');
Route::get('add/friend/{id}', 'HomeController@add_friend');




