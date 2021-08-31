<?php

namespace App\Http\Controllers;

use App\Services\LoanService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;

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
        return view('allLoans', [
            'loans' => $this->loanService->getBaseLoans(),
            'yourLoans' => $this->loanService->getUserLoans()
        ]);
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
            'sum' => Arr::get($request, 'sum', null),
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
        $response = $this->loanService->accept(Arr::get($request, 'sum', null), $id);
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.takeLoan',
                ['id' => $id]))->with('success', Arr::get($response, 1, null));
        } else {
            return redirect(route('user.takeLoan',
                ['id' => $id]))->withErrors(['error' => Arr::get($response, 1, null)]);
        }
    }
}
