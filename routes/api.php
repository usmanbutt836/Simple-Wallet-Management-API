<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
// Users API
Route::post('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
// Wallet API
Route::post('wallets/{userId}/deposit', [WalletController::class, 'deposit']);
Route::post('wallets/{userId}/withdraw', [WalletController::class, 'withdraw']);
// Transactions API
Route::post('transactions/transfer', [TransactionController::class, 'transfer']);
Route::get('transactions/{userId}', [TransactionController::class, 'getTransactions']);

