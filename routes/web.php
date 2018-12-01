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
    Route::get('/subscriptions', 'Admin\AdminSubscriptionController@index')->name('admin.subscriptions');
    //create 1. pindah ke form dl, 2.lakukan store di function lain
    Route::get('/subscription/create', 'Admin\AdminSubscriptionController@create')->Name('admin.subscription.create');
    // detail
    Route::post('/subscription', 'Admin\AdminSubscriptionController@store')->name('admin.subscription.store');
    Route::get('/subscription/{id}', 'Admin\AdminSubscriptionController@show')->name('admin.subscription.detail');
    Route::post('/subscription/{id}', 'Admin\AdminSubscriptionController@filter')->name('admin.subscription.filter');
    //update 1.pindah ke form dl, 2.lakukan update di function lain
    Route::get('/subscription/{id}/edit', 'Admin\AdminSubscriptionController@edit')->name('admin.subscription.edit');
    Route::put('/subscription/{id}', 'Admin\AdminSubscriptionController@update')->name('admin.subscription.update');
    // //delete
    Route::delete('/subscription/{id}/delete', 'Admin\AdminSubscriptionController@delete')->name('admin.subscription.delete');
    // end subscription

    // store
    Route::get('/organizations', 'Admin\AdminOrganizationController@index')->name('admin.organizations');
    // search
    Route::get('/organization/search', 'Admin\AdminOrganizationController@search')->name('admin.organization.search');
    // detail
    Route::get('/organization/{id}', 'Admin\AdminOrganizationController@show')->name('admin.organization.detail');
    Route::post('/organization', 'Admin\AdminOrganizationController@filter')->name('admin.organization.filter');
    // meng aktifkan subscription Organization
    Route::put('/organization/{id}', 'Admin\AdminOrganizationController@active')->name('admin.organization.activate');
    // menambah masa aktif
    Route::put('/organization/{id}/extend', 'Admin\AdminOrganizationController@extend')->name('admin.organization.extend');
    // end organization

    // user
    Route::get('/users', 'Admin\AdminUserController@index')->name('admin.user');
    // search
    Route::get('/user/search', 'Admin\AdminUserController@search')->name('admin.user.search');
    Route::get('/user/{id}', 'Admin\AdminUserController@show')->name('admin.user.detail');
    // end user

    // paymet
    Route::get('/payments', 'Admin\AdminPaymentController@index')->name('admin.payments');
    // filter yang sudah bayar
    Route::get('payment/paid', 'Admin\AdminPaymentController@paid')->name('admin.payment.paid');
    // search
    Route::get('/payment/search', 'Admin\AdminPaymentController@search')->name('admin.payment.search');
    //detail unutk memeriksa proof
    Route::get('/payment/{id}', 'Admin\AdminPaymentController@show')->name('admin.payment.detail');
    // endpayment

    // contact
    // not yet to use
    // Route::get('/contact', 'Admin\AdminContactController@index');
    // Route::get('/contact/{id}', 'Admin\AdminContactController@show');
    // end contact

    // salesOrder
    // not yrt to use
    // Route::get('/sales_order', 'AdminSalesController@index');

    // end sales order

    // report
    Route::get('/report', 'Admin\AdminReportController@index')->name('admin.report');
    Route::post('/report', 'Admin\AdminReportController@searchBy')->name('admin.report.searchBy');
    // detail
    Route::get('/report/{id}', 'Admin\AdminReportController@show')->name('admin.report.detail');
    // end report
});
// end admin page

// user page
// subscription
Route::get('/subscriptions', 'User\SubscriptionController@index')->name('subscriptions');
// detail
Route::get('/subscription/{id}/detail', 'User\SubscriptionController@show')->name('subscription.detail');
// untuk membeli dan membuat payment
Route::post('/subscription/{id}/cart', 'User\SubscriptionController@buy')->name('subscription.buy');
// upload bukti transfer
Route::get('/subscription/{id}/buy/proof', 'User\SubscriptionController@uploadProof')->name('subscription.buy.upload.proof');
Route::get('/subscription/{id}/extend/proof', 'User\SubscriptionController@uploadProof')->name('subscription.extend.upload.proof');
// store hasil upload bukti transfer
Route::post('/subscription/{id}/buy/proof', 'User\SubscriptionController@storeProof')->name('subscription.store.proof');
// untuk masuk link upload proof dan lihat detail
Route::get('subscription/cart', 'User\SubscriptionController@cart')->name('subscription.cart');
// unutk melihat keranjang
Route::get('/subscription/payment/proof', 'User\SubscriptionController@cart')->name('subscription.payment');
// end subscription


// organization
Route::get('/home', 'User\OrganizationController@index')->middleware('gate');
// create Organization
Route::get('/create', 'User\OrganizationController@create')->name('organization.create');
Route::post('/organization', 'User\OrganizationController@store')->name('organization.store');
// update Organization
Route::put('/organization/{id}', 'User\OrganizationController@update')->name('organization.update');
// //delete
Route::delete('/organization/{id}/delete', 'User\OrganizationController@delete')->name('organization.delete');
// end Organization


// contact
Route::get('/contacts', 'User\ContactController@index')->name('contacts');
// search
Route::get('/contact/search', 'User\ContactController@search')->name('contact.search');
// create contact
Route::get('/contact/create', 'User\ContactController@create')->name('contact.create');
Route::post('/contact', 'User\ContactController@store')->name('contact.store');
// edit contact
Route::get('/contact/{id}/edit', 'User\ContactController@edit')->name('contact.edit');
Route::put('/contact/{id}', 'User\ContactController@update')->name('contact.update');
// //delete
Route::delete('/contact/{id}/delete', 'User\ContactController@delete');
// end contact


// items
Route::get('/items', 'User\ItemController@index')->name('items');
// search
Route::get('/item/search', 'User\ItemController@search')->name('item.search');
// create item
Route::get('/item/create', 'User\ItemController@create')->name('item.create');
Route::post('/item', 'User\ItemController@store')->name('item.store');
// edit item
Route::get('/item/{id}/edit', 'User\ItemController@edit')->name('item.edit');
Route::put('/item/{id}', 'User\ItemController@update')->name('item.update');
// //delete
Route::delete('/item/{id}/delete', 'User\ItemController@delete')->name('item.delete');
// end item

// sales order
Route::get('/sales_orders', 'User\SalesOrderController@index')->name('sales.orders');
// Route::get('/cari', 'SalesOrderController@loadData');
// search
Route::get('/sales_order/search', 'User\SalesOrderController@search')->name('sales.order.search');
// ngebuat sales order
Route::get('/sales_order/create', 'User\SalesOrderController@create')->name('sales.order.create');
// Route::get('/sales_order/create', 'SalesOrderController@bill');
// menyimpan hasil create sales order, kemudian diarahkan ke bill
Route::post('/sales_order', 'User\SalesOrderController@store')->name('sales.order.store');
// lihat detail
Route::get('/sales_order/{id}/detail', 'User\SalesOrderController@show')->name('sales.order.detail');
Route::get('/sales_order/{id}', 'User\SalesOrderController@bill')->name('sales.order.bill');
// edit sales order
// Route::get('/sales_order/{id}/edit', 'SalesOrderController@edit');
// unutk update customer
Route::put('/sales_order/{id}', 'User\SalesOrderController@update')->name('sales.order.update');
//delete
Route::delete('sales_order/{id}/delete', 'User\SalesOrderController@delete')->name('sales.order.delete');

//tambah detail invoice
// Route::get('/sales_order/{salesOrder_id}/invoice/{invoice_id}/create', 'InvoiceController@create')->name('invoice.create');
// unutk menambahkan item pada bill
Route::post('/sales_order/{salesOrder_id}/invoice/{invoice_id}/add_item', 'User\InvoiceController@store')->name('invoice.store');
//edit detail invoice
// Route::get('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/edit', 'InvoiceController@edit');
//decrease qty item
Route::put('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/decrease', 'User\InvoiceController@decrease')->name('invoice.decrease');
//increase qty item
Route::put('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/increase', 'User\InvoiceController@increase')->name('invoice.increase');
// Route::put('/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}', 'InvoiceController@update');
// delete item pada bill
Route::delete('/sales_order/{salesOrder_id}/invoice/{invoice_id}/invoice_detail/{invoiceDetail_id}/delete', 'User\InvoiceController@delete')->name('invoice.delete');
// end sales order

// report
Route::get('/report', 'User\ReportController@salesByItemMonth')->name('report.item');
Route::post('/report/item', 'User\ReportController@Item')->name('report.item.by');

Route::get('/report/customer', 'User\ReportController@salesByCustomerMonth')->name('report.customer');
Route::post('/report/customer', 'User\ReportController@Customer')->name('report.customer.by');
// end report

// employe
Route::get('/employes', 'User\EmployeController@index')->name('employes');
// tambah employe
Route::get('/employe/create', 'User\EmployeController@create')->name('employe.create');
Route::post('/employe', 'User\EmployeController@store')->name('employe.store');
// end user page


// profile
Route::get('profile', 'UserController@index')->name('profile');
Route::put('profile/{id}', 'UserController@update')->name('profile.update');
// end profile

// ganti password
Route::group(['middleware' => 'auth'], function () {
    Route::get('password', 'PasswordController@change')->name('password.change');
    Route::put('password', 'PasswordController@update')->name('password.update');
});

// autocompleate
Route::get('/autoload', 'BaseController@index');
Route::get('/autoload/cari', 'BaseController@loadData')->name('autocomplete.cari');
Route::get('/autocomplete/fetch', 'BaseController@fetch')->name('autocomplete.fetch');
Route::get('/autocomplete/fetch/item', 'BaseController@fetchItem')->name('autocomplete.fetch.item');
Route::get('/autocomplete/fetch/organization', 'BaseController@fetchOrganization')->name('autocomplete.fetch.organization');
Route::get('/autocomplete/fetch/user', 'BaseController@fetchUser')->name('autocomplete.fetch.user');

Route::get('tes', function () {
    return view('tes/number');
});
