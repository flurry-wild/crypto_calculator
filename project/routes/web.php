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
Route::get('fiat_payments/get_sum', [\App\Http\Controllers\BuyCryptoController::class, 'getSum']);
Route::post('fiat_payments/buy_for_fiat', [\App\Http\Controllers\BuyCryptoController::class, 'buyForFiat']);
Route::get('fiat_payments/courses', [\App\Http\Controllers\BuyCryptoController::class, 'courses']);
Route::post('crypto_payments/buy_crypto', [\App\Http\Controllers\BuyCryptoController::class, 'buyCrypto']);
Route::patch('crypto_payments/sell_crypto/{id}', [\App\Http\Controllers\BuyCryptoController::class, 'sellCrypto']);
Route::get('crypto_payments/purchases', [\App\Http\Controllers\BuyCryptoController::class, 'purchases']);
Route::get('crypto_payments/chart/{coin}', [\App\Http\Controllers\BuyCryptoController::class, 'chart']);
Route::get('crypto_payments/purchases/{coin}', [\App\Http\Controllers\BuyCryptoController::class, 'purchases']);
