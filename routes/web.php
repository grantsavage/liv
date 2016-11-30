<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Welcome route
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Auth::routes();

// Home route
Route::get('/home', 'HomeController@index');

// Get and post posts
Route::get('/posts', 'PostController@index');
Route::post('/posts', 'PostController@store');

// Like route
Route::post('/posts/{post}/likes', "PostLikeController@store");

// Profile routes
Route::get('/user/{username}', "ProfileController@getProfile");
Route::get('/profile/edit', 'ProfileController@edit');
Route::post('/profile/edit', 'ProfileController@update');

// Settings routes
Route::get('/settings',"SettingsController@index");

// Notification Routes
Route::get('/notifications', "NotificationController@getUserUnreadNotifications");
Route::get('/notifications/clear', "NotificationController@setNotificationsAsRead");

// Search
Route::get('/search', "SearchController@search");

// Friends
Route::get('/friends', "FriendController@friends");
Route::get('/friends/add/{username}', "FriendController@getAdd");
Route::get('/friends/accept/{username}', "FriendController@getAccept");
Route::get('/friends/decline/{username}', 'FriendController@getDecline');
Route::post('/friends/delete/{username}', "FriendController@postDelete");