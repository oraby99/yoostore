<?php

//use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\Orders\OrderController;
use App\Http\Controllers\Api\Payment\FatoorahController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\CartController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\CheckoutController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\OrderdetailController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\SuccessController;
use App\Http\Controllers\web\TrackController;
use Illuminate\Http\Request;
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
    return redirect()->route('index');
});

Auth::routes();
Route::get('/callback', [FatoorahController::class, 'callback']);
Route::get('/errorurl', [FatoorahController::class, 'errorurl']);
Route::get('/payment-success', function (Request $request) {
    $invoiceId = $request->query('invoiceId');
    $paymentId = $request->query('paymentId');
    
    return view('payment.success', [
        'invoiceId' => $invoiceId,
        'paymentId' => $paymentId,
    ]);
})->name('payment.success');


Route::get('/payment-failure', function () {
    return view('payment.failure');
})->name('payment.failure');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/orders/{order}/pdf', [OrderController::class, 'generatePdf'])->name('orders.pdf');

Route::get('/index', [HomeController::class, 'index'])->name('index');
Route::get('/product/{id}', [ProductController::class, 'index'])->name('product');
Route::get('/category/{id}' , [CategoryController::class, 'index' ])->name('category');
Route::get('/signup', [AuthController::class, 'signupView'])->name('signup');
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::get('/verifyemail', [AuthController::class, 'verifyemail'])->name('verifyemail');




Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::view('/settings', 'yoostore.dashboard.settings')->name('settings');
    Route::get('/success', [SuccessController::class, 'index'])->name('success');
    Route::get('/track', [TrackController::class, 'index'])->name('track');
    Route::get('/orderDetails/{id}', [OrderdetailController::class, 'index'])->name('orderDetails');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::view('/orderHistory', 'yoostore.dashboard.orderHistory')->name('orderHistory');
    Route::view('/browsingHistory', 'yoostore.dashboard.browsingHistory')->name('browsingHistory');
    Route::view('/faq', 'yoostore.faq')->name('faq');
    Route::view('/wishlist', 'yoostore.wishlist')->name('wishlist');
Route::post('/order/cancel/{id}', [OrderdetailController::class, 'cancelOrder'])->name('order.cancel');

});
Route::get('/import-products', function () {
    return view('cv');
});
Route::post('/import-products', [ImportController::class, 'import'])->name('import.products');