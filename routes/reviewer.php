<?php

use Illuminate\Support\Facades\Route;

Route::name('reviewer.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('reviewer.login');
        })->name('index');
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.store');
        Route::post('logout', 'LoginController@logout')->name('logout');
        Route::middleware('smtp')->group(function () {
            Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        });
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
        Route::middleware('auth:reviewer')->group(function () {
            Route::get('2fa/verify', 'TwoFactorController@show2FaVerifyForm')->name('2fa.verify');
            Route::post('2fa/verify', 'TwoFactorController@verify2fa');
        });
    });
    Route::middleware(['auth:reviewer', '2fa.verify:reviewer', 'demo'])->group(function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::name('items.')->prefix('items')->group(function () {
            Route::name('updated.')->prefix('updated')->group(function () {
                Route::get('/', 'ItemUpdatedController@index')->name('index');
                Route::get('{id}/review', 'ItemUpdatedController@review')->name('review');
                Route::get('{id}/history', 'ItemUpdatedController@history')->name('history');
                Route::get('{id}/action', 'ItemUpdatedController@action')->name('action');
                Route::post('{id}/action', 'ItemUpdatedController@actionUpdate');
                Route::post('{id}/download', 'ItemUpdatedController@download')->name('download');
            });
            Route::get('/', 'ItemController@index')->name('index');
            Route::get('{status}', 'ItemController@status')->name('status');
            Route::get('{id}/review', 'ItemController@review')->name('review');
            Route::get('{id}/history', 'ItemController@history')->name('history');
            Route::get('{id}/action', 'ItemController@action')->name('action');
            Route::post('{id}/action', 'ItemController@actionUpdate');
            Route::post('{id}/download', 'ItemController@download')->name('download');
        });
        Route::name('settings.')->prefix('settings')->group(function () {
            Route::get('/', 'SettingsController@index')->name('index');
            Route::post('details', 'SettingsController@updateDetails')->name('details');
            Route::post('password', 'SettingsController@updatePassword')->name('password');
            Route::post('2fa/enable', 'SettingsController@enable2FA')->name('2fa.enable');
            Route::post('2fa/disable', 'SettingsController@disable2FA')->name('2fa.disable');
        });
    });
});
