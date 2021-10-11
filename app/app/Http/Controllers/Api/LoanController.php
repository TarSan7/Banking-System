<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LoanService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * @var LoanService
     */
    private $loanService;

    /**
     * @param LoanService $loanService
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => "Unauthorized"
            ], 401);
        }
        return response()->json([
            'status' => 'success',
            'loans' => $this->loanService->getBaseLoans(),
            'yourLoans' => $this->loanService->getUserLoans()
        ]);
    }

    public function accept(Request $request, $lang, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ]);
        }
        $response = $this->loanService->accept(Arr::get($request, 'sum', null), $id);
        return response()->json([
            'response' => Arr::get($response, 1, null)
        ]);
    }
}
