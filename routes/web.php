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
    Route::get('/subscription', 'AdminSubscriptionController@index');
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

    // store
    Route::get('/store', 'AdminStoreController@index');
    // detail owner
    Route::put('/store/{id}', 'AdminStoreController@active');
    Route::put('/store/{id}/extend', 'AdminStoreController@extend');
    // end store

    // user
    Route::get('/user', 'AdminUserController@index');
    Route::get('/user/{id}', 'AdminUserController@show');
    // end user

    // contact
    Route::get('/contact', 'AdminContactController@index');
    Route::get('/contact/{id}', 'AdminContactController@show');
    // end contact

});
// end admin page

// user page
// subscription
Route::get('/subscription', 'SubscriptionController@index');
Route::get('/subscription/{id}/pilih', 'SubscriptionController@show');
Route::post('/subscription/beli', 'SubscriptionController@beli');
// end subscription


// store
Route::get('/home', 'StoreController@index')->middleware('gate');
// create store
Route::get('/create', 'StoreController@create');
Route::post('/store', 'StoreController@store');
// update store
Route::put('/store/{id}', 'StoreController@update');
// //delete
Route::delete('/store/{id}/delete', 'StoreController@delete');
// end store


// contact
Route::get('/contact', 'ContactController@index');
// create contact
Route::get('/contact/create', 'ContactController@create');
Route::post('/contact', 'ContactController@store');
// edit contact
Route::get('/contact/{id}/edit', 'ContactController@edit');
Route::put('/contact/{id}', 'ContactController@update');
// //delete
Route::delete('/contact/{id}/delete', 'ContactController@delete');
// end contact


// items
Route::get('/item', 'ItemController@index');
// create item
Route::get('/item/create', 'ItemController@create');
Route::post('/item', 'ItemController@store');
// edit item
Route::get('/item/{id}/edit', 'ItemController@edit');
Route::put('/item/{id}', 'ItemController@update');
// //delete
Route::delete('/item/{id}/delete', 'ItemController@delete');
// end item

// sales order
Route::get('/sales_order', 'SalesOrderController@index');

// end sales order


// end user page
