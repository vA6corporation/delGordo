<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

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


// Auth::routes(['register' => false]);
Auth::routes();

Route::get('/', function() {
    return view('welcome');
});

Route::get('/api/{officeId}/setOffice', function($officeId) {
    session(['officeId' => $officeId]);
    return ['ok' => true];
});

Route::get('/api/products/withInventory', 'ProductController@withInventory');
Route::post('/api/products/image', 'ProductController@storeImage');
Route::get('/api/products/images/{id}', 'ProductController@getImage');
Route::get('/api/customers/byDni', 'CustomerController@byDni');
Route::get('/api/shoppings/removeAll', 'ShoppingController@removeAll');
Route::get('/api/products/{id}/inventoryAll', 'ProductController@inventoryAll');

Route::get('/api/products/{key}/search', 'ProductController@search');

Route::get('/api/sales/{id}/delivery', 'SaleController@delivery');
Route::get('/api/sales/all', 'SaleController@all');
Route::get('/api/sales/{id}/dispatched', 'SaleController@dispatched');
Route::post('/api/sales/deleteSale', 'SaleController@deleteSale');
Route::post('/api/sales/shop', 'SaleController@shop');
Route::post('/api/sales/shopPayment', 'SaleController@shopPayment');
Route::get('/api/sales/find', 'SaleController@find');
Route::put('/api/sales/{id}/withInventory', 'SaleController@updateWithInventory');
Route::put('/api/sales/pay', 'SaleController@pay');
Route::get('/api/sales/dispatchedSales', 'SaleController@dispatchedSales');
Route::get('/api/sales/deliverySales', 'SaleController@deliverySales');

Route::get('/api/products/all', 'ProductController@all');
Route::get('/api/deletedReasons/all', 'DeletedReasonController@all');
Route::get('/api/users/session', 'UserController@session');
Route::get('/api/customers/{dni}/dni', 'CustomerController@getDni');
Route::get('/api/users/dealers', 'UserController@dealers');

Route::get('/api/deliveries/all', 'DeliveryController@all');
Route::get('/api/disableds/shop', 'DisabledController@shop');

Route::apiResources([
    'api/disableds' => 'DisabledController',
    'api/offices' => 'OfficeController',
    'api/deliveryman' => 'DeliverymanController',
    'api/credentials' => 'CredentialController',
    'api/users' => 'UserController',
    'api/paymentMethods' => 'PaymentMethodController',
    'api/emails' => 'EmailController',
    'api/products' => 'ProductController',
    'api/categories' => 'CategoryController',
    'api/subCategories' => 'SubCategoryController',
    'api/shoppings' => 'ShoppingController',
    'api/inventories' => 'InventoryController',
    'api/customers' => 'CustomerController',
    'api/sales' => 'SaleController',
    'api/checkout' => 'CheckoutController',
    'api/deliveries' => 'DeliveryController',
    'api/deletedReasons' => 'DeletedReasonController',
]);


Route::post('/api/logout', 'Auth\LoginController@logout');

Route::get('/store', function () {
    return view('store');
});

Route::get('/store/{id}', function () {
    return view('store');
});

Route::get('/payment', function () {
    return view('store');
});

Route::get('/{saleId}/checkoutDetails', function () {
    return view('store');
});

Route::get('/{saleId}/checkout', function () {
    return view('store');
});

Route::get('/checkout', function () {
    return view('store');
});

Route::get('/shopping', function () {
    return view('store');
});

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
