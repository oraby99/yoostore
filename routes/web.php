<?php

//use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\Payment\FatoorahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/callback', [FatoorahController::class, 'callback']);
Route::get('/errorurl', [FatoorahController::class, 'errorurl']);
Route::get('/payment-success', function () {
    return view('payment.success');
})->name('payment.success');

Route::get('/payment-failure', function () {
    return view('payment.failure');
})->name('payment.failure');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
