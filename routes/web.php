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

Route::apiResources([
    'api/products' => 'ProductController',
    'api/categories' => 'CategoryController',
    'api/shoppings' => 'ShoppingController',
]);

Route::post('/api/products/image', 'ProductController@storeImage');
Route::get('/api/products/images/{id}', 'ProductController@getImage');

Route::post('/api/logout', 'Auth\LoginController@logout');

Route::get('/store', function () {
    return view('storeApp');
});

Route::get('/payment', function () {
    return view('storeApp');
});

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
  