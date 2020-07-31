<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/phpinfo', function () {
    return phpinfo();
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/api/products/withInventory', 'ProductController@withInventory');
Route::post('/api/products/image', 'ProductController@storeImage');
Route::get('/api/products/images/{id}', 'ProductController@getImage');
Route::get('/api/customers/byDni', 'ProductController@byDni');
Route::get('/api/shoppings/removeAll', 'ShoppingController@removeAll');
Route::get('/api/products/{id}/inventoryAll', 'ProductController@inventoryAll');
Route::get('/api/sales/{id}/delivery', 'SaleController@delivery');
Route::post('/api/sales/deleteSale', 'SaleController@deleteSale');

Route::apiResources([
    'api/products' => 'ProductController',
    'api/categories' => 'CategoryController',
    'api/subCategories' => 'SubCategoryController',
    'api/shoppings' => 'ShoppingController',
    'api/inventories' => 'InventoryController',
    'api/customers' => 'CustomerController',
    'api/sales' => 'SaleController',
    'api/checkout' => 'CheckoutController',
]);


Route::post('/api/logout', 'Auth\LoginController@logout');

Route::get('/store', function () {
    return view('store');
});

Route::get('/payment', function () {
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
  