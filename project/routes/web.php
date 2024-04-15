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

Route::get('/', [\App\Http\Controllers\BuyCryptoController::class, 'index']);
Route::get('fiat_payments', [\App\Http\Controllers\FiatPaymentController::class, 'index']);
Route::delete('fiat_payments/{id}', [\App\Http\Controllers\FiatPaymentController::class, 'delete']);
Route::get('fiat_payments/get_sum', [\App\Http\Controllers\BuyCryptoController::class, 'getSum']);
Route::post('fiat_payments', [\App\Http\Controllers\FiatPaymentController::class, 'create']);
Route::get('fiat_payments/courses', [\App\Http\Controllers\BuyCryptoController::class, 'courses']);
Route::post('crypto_payments', [\App\Http\Controllers\BuyCryptoController::class, 'create']);
Route::patch('crypto_payments/{id}', [\App\Http\Controllers\BuyCryptoController::class, 'update']);
Route::delete('crypto_payments/{id}', [\App\Http\Controllers\BuyCryptoController::class, 'delete']);
Route::get('crypto_payments/purchases', [\App\Http\Controllers\BuyCryptoController::class, 'purchases']);
Route::get('crypto_payments/chart/{coin}', [\App\Http\Controllers\BuyCryptoController::class, 'chart']);
Route::get('crypto_payments/purchases/{coin}', [\App\Http\Controllers\BuyCryptoController::class, 'purchases']);
