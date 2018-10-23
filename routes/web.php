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

// Route::get('/', function () {
//     return view('welcome');
// });

// bawaan dari make:auth
// Auth::routes();
//
// Route::get('/home', 'HomeController@index')->name('home');
// batasan bawaan dari make:auth

Route::get('/', 'IndexController@index');

Auth::routes();

// admin page
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
      return view('admin/index');
    })->middleware('auth', 'admin');

    // subscription
    Route::get('/subscription', "AdminSubscriptionController@index");
    //create 1. pindah ke form dl, 2.lakukan store di function lain
    Route::get('/subscription/create', 'AdminSubscriptionController@create');
    Route::post('/subscription', 'AdminSubscriptionController@store');
    // detail
    Route::get('/subscription/{id}', 'AdminSubscriptionController@show');
    //update 1.pindah ke form dl, 2.lakukan update di function lain
    Route::get('/subscription/{id}/edit', 'AdminSubscriptionController@edit');
    Route::put('/subscription/{id}', 'AdminSubscriptionController@update');
    // //delete
    Route::delete('/subscription/{id}/delete', 'AdminSubscriptionController@delete');
    // end subscription

});
// end admin page

// subscription for user
// Route::get('/home', 'SubscriptionController@home')->name('home');

Route::get('/subscription', 'SubscriptionController@index');

Route::get('/subscription/{id}/pilih', 'SubscriptionController@show');

Route::post('/subscription/beli', 'SubscriptionController@beli');
// end subscription for user

// store for user
Route::get('/home', 'StoreController@index')->middleware('auth', 'gate');
// create store
Route::get('/create', 'StoreController@create');
Route::post('/store', 'StoreController@store');
// update store
Route::put('/store/{id}', 'StoreController@update');
// //delete
Route::delete('/store/{id}/delete', 'StoreController@delete');
// end store for user

// contact for user
Route::get('/contact', 'ContactController@index');

// create store
Route::get('/contact/create', 'ContactController@create')->middleware('get.subscription');
Route::post('/contact', 'ContactController@store');

// edit contact
Route::get('/contact/{id}/edit', 'ContactController@edit');
Route::put('/contact/{id}', 'ContactController@update');
// //delete
Route::delete('/contact/{id}/delete', 'ContactController@delete');
// end contact
