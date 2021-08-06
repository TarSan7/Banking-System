<?php

use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTransferController;
use App\Http\Controllers\PhoneTransferController;

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

Route::get('/', function () {
    return view('index');
})->name('home');

Route::name('user.') -> group(function () {
    Route::get('/private', [LoginController::class, 'toPrivate'])
        ->middleware('auth')->name('private');

    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect(route('user.private'));
        }
        return view('login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/logout', function () {
        Auth::logout();
        return redirect(route('home'));
    })->name('logout');

    Route::get('/registration', function () {
        if (Auth::check()) {
            return redirect(route('user.private'));
        }
        return view('registration');
    })->name('registration');

    Route::post('/registration', [RegisterController::class, 'saveUser']);

    Route::get('/addCard', function () {
        return view('addCard');
    })->name('addCard');

    Route::post('/addCard', [CardController::class, 'addCard']);

    Route::get('/card/{id}', [CardController::class, 'cardInfo'])->name('card');

    Route::get('/transfers', function () {
        return view('chooseTransfer');
    })->name('transfers');

    Route::get('/cardTransfer', [CardTransferController::class, 'goTransfer'])->name('cardTransfer');

    Route::post('/cardTransfer', [CardTransferController::class, 'transferToCard']);

    Route::get('/phoneTransfer/{id}', [PhoneTransferController::class, 'goTransfer'])->name('phoneTransfer');

    Route::post('/phoneTransfer/{id}', [PhoneTransferController::class, 'transferToPhone']);

    Route::get('/allLoans', [LoanController::class, 'allLoans'])->name('allLoans');
});
