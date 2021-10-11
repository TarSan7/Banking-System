<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CardTransferController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\OtherTransferController;
use Illuminate\Support\Facades\Auth;
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

Route::redirect('/', '/en');

Route::group(['middleware' => 'api', 'prefix' => '{locale}'], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', function () {
        if (Auth::check()) {
            return response()->json([
                'message' => 'You are logged in',
                'token' => \Tymon\JWTAuth\Facades\JWTAuth::fromUser(Auth::user())
            ]);
        }
        return response()->json([
            'message' => 'You are not register'
        ]);
    });
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('/login', function () {
        if (Auth::check()) {
            return response()->json(['page' => 'You logged in', app()->getLocale()]);
        }
        return response()->json(['page' => 'Unauthorized', app()->getLocale()]);
    });
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/private', [CardController::class, 'userCards']);
    Route::post('/addCard', [CardController::class, 'addCard']);
    Route::get('/card/{id}', [CardController::class, 'info']);
    Route::post('/cardTransfer', [CardTransferController::class, 'make']);
    Route::post('/otherTransfer/{id}', [OtherTransferController::class, 'make']);

    Route::get('/allLoans', [LoanController::class, 'all']);
    Route::post('/acceptLoan/{id}', [LoanController::class, 'accept']);

    Route::get('/allDeposits', [DepositController::class, 'all']);

    Route::post('/acceptDeposit/{id}', [DepositController::class, 'accept']);
    Route::post('/closeDeposit/{id}', [DepositController::class, 'close']);

    Route::get('/transactions', [CardTransferController::class, 'all']);
});
