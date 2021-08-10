<?php

namespace App\Http\Controllers;

use App\Services\LoanService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Class LoanController
 * @package App\Http\Controllers
 */
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
     * @return Application|Factory|View
     */
    public function all()
    {
        return view('allLoans', ['loans' => $this->loanService->getBaseLoans()]);
    }

    /**
     * @param integer $id
     * @return Application|Factory|View
     */
    public function take($id)
    {
        return view('takeLoan', ['loan' => $this->loanService->oneLoan($id)]);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return Application|Factory|View
     */
    public function details(Request $request, $id)
    {
        return view('loanInfo', [
            'loan' => $this->loanService->oneLoan($id),
            'sum' => $request['sum'],
            'id' => $id
        ]);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return Application|RedirectResponse|Redirector
     */
    public function accept(Request $request, $id)
    {
        $response = $this->loanService->accept($request['sum'], $id);
        if ($response[0] === 'success') {
            return redirect(route('user.takeLoan', ['id' => $id]))->with('success', $response[1]);
        } else {
            return redirect(route('user.takeLoan', ['id' => $id]))->withErrors(['error' => $response[1]]);
        }
    }
}
