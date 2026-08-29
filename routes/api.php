<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::name('api.')->namespace('Api')->middleware('api.disable')->group(function () {
    Route::name('account.')->prefix('account')->group(function () {
        Route::get('details', 'AccountController@details')->name('details');
    });
    Route::name('items.')->prefix('items')->group(function () {
        Route::get('all', 'ItemController@all')->name('all');
        Route::get('item', 'ItemController@item')->name('item');
    });
    Route::name('purchases.')->prefix('purchases')->group(function () {
        Route::post('validation', 'PurchaseController@validation')->name('validation');
    });
});

/*
|--------------------------------------------------------------------------
| Mobile Application API Routes
|--------------------------------------------------------------------------
*/
Route::namespace('Api')->prefix('v1/auth')->name('api.v1.auth.')->group(function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', 'AuthController@profile')->name('profile');
        Route::post('logout', 'AuthController@logout')->name('logout');
    });
});

