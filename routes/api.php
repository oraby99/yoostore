<?php

use App\Http\Controllers\Api\Address\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Banners\BannerProductController;
use App\Http\Controllers\Api\General\FavoriteController;
use App\Http\Controllers\Api\General\ProductHistoryController;
use App\Http\Controllers\Api\General\RateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::controller('App\Http\Controllers\Api\AuthController')->group(function () {

        Route::post('register', 'register')->name('register');

        Route::post('login', 'login')->name('login');

        Route::post('forgot-password', 'forgotPassword')->name('forgot-password');

        Route::post('deleteaccount/{id}', 'deleteaccount')->name('deleteaccount');

        Route::post('change-password', 'changePassword')->name('change-password');

        Route::name('otp.')->prefix('otp')->group(function () {

            Route::post('verify', 'verifyOtp')->name('verify');

            Route::post('resend', 'resendOtp')->name('resend');

        });

    });
    Route::get('/banners-sections',          [BannerProductController::class, 'getBannersWithProducts']);
    Route::get('/fourth-banner-products',    [BannerProductController::class, 'getFourthBannerProducts']);
    Route::get('/products',                  [BannerProductController::class, 'products']);
    Route::get('/categories',                [BannerProductController::class, 'categories']);
    Route::get('/subcategory/{id}',          [BannerProductController::class, 'subcategory']);
    Route::get('/offers',                    [BannerProductController::class, 'getOffers']);
    Route::get('/offers-by-tag',             [BannerProductController::class, 'getOffersByTag']);
    Route::get('/profile-by-tag',            [BannerProductController::class, 'getProfileByTag']);
    Route::get('/product/{productId}/rates', [RateController::class, 'getRatesByProduct']);
    Route::middleware('auth:sanctum')->group(function () {
    Route::post('/product/{productId}',      [BannerProductController::class, 'productById']);
    Route::get('/product-history',           [ProductHistoryController::class, 'getUserHistory']);
    Route::get('/favorites',                 [FavoriteController::class, 'getFavorites']);
    Route::post('/favorite',                 [FavoriteController::class, 'addFavorite']);
    Route::delete('/favorite/{productId}',   [FavoriteController::class, 'removeFavorite']);
    Route::post('/address',                  [AddressController::class, 'store']);
    Route::get('/addresses',                 [AddressController::class, 'index']);
    Route::patch('/address/{id}/default',    [AddressController::class, 'setDefault']);
    Route::post('/product/{productId}/rate', [RateController::class, 'store']);
    //========================================================================================================
    Route::post('/logout',                   [AuthController::class, 'logout']);
    Route::post('/profile/update',           [AuthController::class, 'updateProfile']);

}); 
