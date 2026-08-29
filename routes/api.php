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
| Mobile Application API Routes (v1)
|--------------------------------------------------------------------------
*/
Route::namespace('Api\Mobile')->prefix('v1')->name('api.v1.')->group(function () {

    // Global App Configuration
    Route::get('config', 'ConfigController@index')->name('config');

    // Public Auth Endpoints
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('register', 'AuthController@register')->name('register');
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('social-login', 'AuthController@socialLogin')->name('social-login');
        Route::post('forgot-password', 'AuthController@forgotPassword')->name('forgot-password');
    });

    // Home Discovery & Categories
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('categories', 'HomeController@categories')->name('categories');

    // Catalog & Beats
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', 'ItemController@index')->name('index');
        Route::get('{slug_or_id}', 'ItemController@show')->name('show');
        Route::get('{id}/reviews', 'ItemController@reviews')->name('reviews');
        Route::get('{id}/comments', 'ItemController@comments')->name('comments');
        Route::get('{id}/download-free', 'ItemController@downloadFree')->name('download-free');
    });

    // Public Producer Profiles
    Route::get('producers/{username_or_id}', 'ProducerController@show')->name('producers.show');

    // Subscription & Premium Plans
    Route::get('plans', 'PlanController@index')->name('plans.index');

    // Blog & News
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', 'BlogController@index')->name('index');
        Route::get('{slug}', 'BlogController@show')->name('show');
    });

    // Help Center & FAQs
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('categories', 'HelpController@categories')->name('categories');
        Route::get('article/{slug}', 'HelpController@article')->name('article');
        Route::get('faqs', 'HelpController@faqs')->name('faqs');
    });

    // Authenticated Mobile Routes (Requires Sanctum Bearer Token)
    Route::middleware('auth:sanctum')->group(function () {

        // Auth management
        Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');

        // User Profile, KYC & Author Status
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('profile', 'ProfileController@profile')->name('profile');
            Route::put('profile', 'ProfileController@updateProfile')->name('profile.update');
            Route::post('avatar', 'ProfileController@updateAvatar')->name('avatar.update');
            Route::put('password', 'ProfileController@changePassword')->name('password.update');
            Route::get('kyc', 'ProfileController@kycStatus')->name('kyc.status');
            Route::post('become-author', 'ProfileController@becomeAuthor')->name('become-author');
            Route::put('withdrawal-account', 'ProfileController@updateWithdrawalAccount')->name('withdrawal-account');
            Route::get('following', 'ProducerController@following')->name('following');
            Route::get('subscription', 'PlanController@userSubscription')->name('subscription');

            // Library & Downloads
            Route::get('purchases', 'PurchaseController@index')->name('purchases');
            Route::get('purchases/{id}/download', 'PurchaseController@download')->name('purchases.download');
            Route::get('statements', 'PurchaseController@statements')->name('statements');
        });

        // Wishlist, Reviews & Social Interactions
        Route::get('favorites', 'ItemController@favorites')->name('favorites');
        Route::post('items/{id}/favorite', 'ItemController@toggleFavorite')->name('items.favorite');
        Route::post('items/{id}/reviews', 'ItemController@storeReview')->name('items.reviews.store');
        Route::post('items/{id}/comments', 'ItemController@storeComment')->name('items.comments.store');
        Route::post('producers/{id}/follow', 'ProducerController@toggleFollow')->name('producers.follow');

        // Cart
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', 'CartController@index')->name('index');
            Route::post('add', 'CartController@add')->name('add');
            Route::delete('clear', 'CartController@clear')->name('clear');
            Route::delete('{id}', 'CartController@remove')->name('remove');
        });

        // Mobile Checkout & Payments
        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('gateways', 'CheckoutController@gateways')->name('gateways');
            Route::post('create-transaction', 'CheckoutController@createTransaction')->name('create-transaction');
            Route::post('pay-with-balance', 'CheckoutController@payWithBalance')->name('pay-with-balance');
        });

        // Author / Producer Studio
        Route::prefix('author')->name('author.')->group(function () {
            Route::get('dashboard', 'AuthorController@dashboard')->name('dashboard');
            Route::get('items', 'AuthorController@items')->name('items');
            Route::post('items/upload', 'AuthorController@uploadBeat')->name('items.upload');
            Route::delete('items/{id}', 'AuthorController@deleteBeat')->name('items.delete');
            Route::get('sales', 'AuthorController@sales')->name('sales');
            Route::get('withdrawals', 'AuthorController@withdrawals')->name('withdrawals');
            Route::post('withdrawals/request', 'AuthorController@requestWithdrawal')->name('withdrawals.request');
        });

        // Refunds
        Route::prefix('refunds')->name('refunds.')->group(function () {
            Route::get('/', 'RefundController@index')->name('index');
            Route::post('/', 'RefundController@store')->name('store');
            Route::get('{id}', 'RefundController@show')->name('show');
            Route::post('{id}/reply', 'RefundController@reply')->name('reply');
        });

        // Support Tickets
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', 'TicketController@index')->name('index');
            Route::get('categories', 'TicketController@categories')->name('categories');
            Route::post('/', 'TicketController@store')->name('store');
            Route::get('{id}', 'TicketController@show')->name('show');
            Route::post('{id}/reply', 'TicketController@reply')->name('reply');
        });

    });

});




