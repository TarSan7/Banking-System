<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class LoanController
 * @package App\Http\Controllers
 */
class LoanController extends Controller
{


    public function allLoans()
    {
        return view('allLoans');
    }
}
