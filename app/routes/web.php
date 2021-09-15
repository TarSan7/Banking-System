<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTransferController;
use App\Http\Controllers\OtherTransferController;
use Illuminate\Support\Facades\Session;

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
Route::redirect('/', '/en');

Route::prefix('{locale}')->group(function () {

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
            return redirect(route('home', app()->getLocale()));
        })->name('logout');

        Route::get('/registration', function () {
            if (Auth::check()) {
                return redirect(route('user.private', app()->getLocale()));
            }
            return view('registration');
        })->name('registration');

        Route::post('/registration', [RegisterController::class, 'saveUser']);

        Route::get('/addCard', function () {
            return view('addCard');
        })->name('addCard');

        Route::post('/addCard', [CardController::class, 'add']);

        Route::get('/card/{id}', [CardController::class, 'info'])->name('card');

        Route::get('/transfers', function () {
            return view('chooseTransfer');
        })->name('transfers');

        Route::get('/cardTransfer', [CardTransferController::class, 'index'])->name('cardTransfer');

        Route::post('/cardTransfer', [CardTransferController::class, 'make']);

        Route::get('/otherTransfer/{id}', [OtherTransferController::class, 'index'])->name('otherTransfer');

        Route::post('/otherTransfer/{id}', [OtherTransferController::class, 'make']);

        Route::get('/allLoans', [LoanController::class, 'all'])->name('allLoans');

        Route::get('/takeLoan/{id}', [LoanController::class, 'take'])->name('takeLoan');

        Route::post('/takeLoan/{id}', [LoanController::class, 'details']);

        Route::post('/acceptLoan/{id}', [LoanController::class, 'accept'])->name('acceptLoan');

        Route::get('/transactions', [TransactionController::class, 'all'])->name('transactions');

        Route::get('/allDeposits', [DepositController::class, 'all'])->name('allDeposits');

        Route::get('/takeDeposit/{id}', [DepositController::class, 'take'])->name('takeDeposit');

        Route::post('/takeDeposit/{id}', [DepositController::class, 'details']);

        Route::post('/acceptDeposit/{id}', [DepositController::class, 'accept'])->name('acceptDeposit');

        Route::post('/closeDeposit/{id}', [DepositController::class, 'close'])->name('closeDeposit');

    });

//    Route::('locale', function($route)
//    {
//        $locale = $route->getParameter('locale');
//
//        Session::put('locale', $locale);
//    });
});


