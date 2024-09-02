<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
