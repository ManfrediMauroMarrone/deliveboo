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
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/show/{slug}', 'HomeController@show')->name('guest.show');
Route::get('/checkout', 'HomeController@checkout')->name('guest.checkout');

Route::middleware('auth')->namespace('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('/restaurants', 'RestaurantController');
    Route::resource('/dishes', 'DishController');
    Route::get('/orders', 'OrderController@index')->name('orders');
});
