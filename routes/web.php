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
    Route::get('/subscription', 'AdminSubscriptionController@index')->name('admin.subscription');
    //create 1. pindah ke form dl, 2.lakukan store di function lain
    Route::get('/subscription/create', 'AdminSubscriptionController@create')->Name('admin.subscription.create');
    Route::post('/subscription', 'AdminSubscriptionController@store')->name('admin.subscription.store');
    // detail
    Route::get('/subscription/{id}', 'AdminSubscriptionController@show')->name('admin.subscription.detail');
    Route::post('/subscription/{id}', 'AdminSubscriptionController@filter')->name('admin.subscription.filter');
    //update 1.pindah ke form dl, 2.lakukan update di function lain
    Route::get('/subscription/{id}/edit', 'AdminSubscriptionController@edit')->name('admin.subscription.edit');
    Route::put('/subscription/{id}', 'AdminSubscriptionController@update')->name('admin.subscription.update');
    // //delete
    Route::delete('/subscription/{id}/delete', 'AdminSubscriptionController@delete')->name('admin.subscription.delete');
    // end subscription

    // store
    Route::get('/store', 'AdminStoreController@index');
    // search
    Route::get('/store/search', 'AdminStoreController@search');
    // detail
    Route::get('/store/{id}', 'AdminStoreController@show')->name('admin.store.detail');
    Route::post('/store', 'AdminStoreController@filter');
    // meng aktifkan subscription store
    Route::put('/store/{id}', 'AdminStoreController@active');
    // menambah masa aktif
    Route::put('/store/{id}/extend', 'AdminStoreController@extend');
    // end store

    // user
    Route::get('/user', 'AdminUserController@index');
    // search
    Route::get('/user/search', 'AdminUserController@search');
    Route::get('/user/{id}', 'AdminUserController@show');
    // end user

    // paymet
    Route::get('/payment', 'AdminPaymentController@index');
    // filter yang sudah bayar
    Route::get('payment/paid', 'AdminPaymentController@paid');
    // search
    Route::get('/payment/search', 'AdminPaymentController@search');
    //detail unutk memeriksa proof
    Route::get('/payment/{id}', 'AdminPaymentController@show');
    // endpayment

    // contact
    Route::get('/contact', 'AdminContactController@index');
    Route::get('/contact/{id}', 'AdminContactController@show');
    // end contact

    // salesOrder
    Route::get('/sales_order', 'AdminSalesController@index');

    // end sales order

    // report
    Route::get('/report', 'AdminReportController@index')->name('admin.report');

});
// end admin page

// user page
// subscription
Route::get('/subscription', 'SubscriptionController@index');
// detail
Route::get('/subscription/{id}/detail', 'SubscriptionController@show');
// untuk membeli dan membuat payment
Route::post('/subscription/{id}/cart', 'SubscriptionController@buy');
// upload bukti transfer
Route::get('/subscription/{id}/buy/proof', 'SubscriptionController@uploadProof');
Route::get('/subscription/{id}/extend/proof', 'SubscriptionController@uploadProof');
// store hasil upload bukti transfer
Route::post('/subscription/{id}/buy/proof', 'SubscriptionController@storeProof');
// untuk masuk link upload proof dan lihat detail
Route::get('subscription/cart', 'SubscriptionController@cart');
// unutk melihat keranjang
Route::get('/subscription/payment/proof', 'SubscriptionController@cart');
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
// search
Route::get('/contact/search', 'ContactController@search');
// create contact
Route::get('/contact/create', 'ContactController@create');
Route::post('/contact', 'ContactController@store');
// edit contact
Route::get('/contact/{id}/edit', 'ContactController@edit')->name('contact_detail');
Route::put('/contact/{id}', 'ContactController@update');
// //delete
Route::delete('/contact/{id}/delete', 'ContactController@delete');
// end contact


// items
Route::get('/item', 'ItemController@index');
// search
Route::get('/item/search', 'ItemController@search');
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
Route::get('/cari', 'SalesOrderController@loadData');
// search
Route::get('/sales_order/search', 'SalesOrderController@search');
// ngebuat sales order
Route::get('/sales_order/create', 'SalesOrderController@create');
Route::post('/sales_order', 'SalesOrderController@store');
// lihat detail
Route::get('/sales_order/{id}/detail', 'SalesOrderController@show')->name('sales_order_detail');
Route::get('/sales_order/{id}', 'SalesOrderController@bill')->name('sales_order_bill');
// edit sales order
Route::get('/sales_order/{id}/edit', 'SalesOrderController@edit');
Route::put('/sales_order/{id}', 'SalesOrderController@update');
//delete
Route::delete('sales_order/{id}/delete', 'SalesOrderController@delete');

//tambah detail invoice
Route::get('/sales_order/{salesOrder_id}/invoice/{invoice_id}/create', 'InvoiceController@create');
Route::post('/sales_order/{salesOrder_id}/invoice/{invoice_id}/add_item', 'InvoiceController@store');
//edit detail invoice
Route::get('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/edit', 'InvoiceController@edit');
//decrease qty item
Route::put('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/decrease', 'InvoiceController@decrease');
//increase qty item
Route::put('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/increase', 'InvoiceController@increase');
Route::put('/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}', 'InvoiceController@update');
// delete
Route::delete('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/delete', 'InvoiceController@delete');
// end sales ordecari',r

// report
Route::get('/report', 'ReportController@salesByItemMonth');
Route::post('/report/item', 'ReportController@Item');

Route::get('/report/customer', 'ReportController@salesByCustomerMonth');
Route::post('/report/customer', 'ReportController@Customer');
// end report

// profile
Route::get('profile', 'UserController@index');
Route::put('profile/{id}', 'UserController@update');
// end profile

// end user page

// ganti password
Route::group(['middleware' => 'auth'], function () {
    Route::get('password', 'PasswordController@change')->name('password.change');
    Route::put('password', 'PasswordController@update')->name('password.update');
});

// coba autocompleate
Route::get('/autoload', 'BaseController@index');
Route::get('/autoload/cari', 'BaseController@loadData')->name('autocomplete.cari');
Route::get('/autocomplete/fetch', 'BaseController@fetch')->name('autocomplete.fetch');
Route::get('/autocomplete/fetch/item', 'BaseController@fetchItem')->name('autocomplete.fetch.item');
Route::get('/autocomplete/fetch/store', 'BaseController@fetchStore')->name('autocomplete.fetch.store');
Route::get('/autocomplete/fetch/user', 'BaseController@fetchUser')->name('autocomplete.fetch.user');
