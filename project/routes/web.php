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
Route::get('fiat_payments/get_crypto_coins', [\App\Http\Controllers\BuyCryptoController::class, 'getCryptoCoins']);
Route::post('crypto_payments/buy_crypto', [\App\Http\Controllers\BuyCryptoController::class, 'buyCrypto']);
Route::get('crypto_payments/purchases', [\App\Http\Controllers\BuyCryptoController::class, 'purchases']);
Route::get('crypto_payments/chart/{coin}', [\App\Http\Controllers\BuyCryptoController::class, 'chart']);
Route::get('crypto_payments/purchases/{coin}', [\App\Http\Controllers\BuyCryptoController::class, 'purchases']);
Route::get('crypto_payments/test', [\App\Http\Controllers\BuyCryptoController::class, 'test']);
