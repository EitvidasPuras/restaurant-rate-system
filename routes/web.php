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

Auth::routes();

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/hub', 'TotalController@hubIndex')->name('hub');
});

Route::group(['middleware' => ['jwt.verify', 'check.admin'], 'prefix' => 'admin/'], function () {
    Route::get('panel', 'TotalController@adminPanelIndex')->name('adminPanel');
    Route::get('restaurants', 'TotalController@adminRestaurantsIndex')->name('adminRestaurants');
    Route::get('users', 'TotalController@adminUsersIndex')->name('adminUsers');
});

Route::get('/loginwithtoken', 'JwtController@loginUsingToken')->middleware('jwt.verify')->name('loginWithToken');
Route::get('/{id}', 'TotalController@showRestaurant')->name('showRestaurant');
Route::get('/', 'TotalController@homeIndex')->name('home');
