<?php

namespace App\Http\Controllers;

use App\Services\CardService;
use App\Services\DepositService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;

class DepositController extends Controller
{
    /**
     * @var DepositService
     * @var CardService
     */
    private $depositService, $cardService;

    /**
     * @param DepositService $depositService
     * @param CardService $cardService
     */
    public function __construct(DepositService $depositService, CardService $cardService)
    {
        $this->depositService = $depositService;
        $this->cardService = $cardService;
    }

    /**
     * @return Application|Factory|View
     */
    public function all()
    {
        return view('allDeposits', [
           'deposits' => $this->depositService->getBaseDeposits(),
           'yourDeposits' => $this->depositService->getUserDeposits()
        ]);
    }

    /**
     * @param integer $id
     * @return Application|Factory|View
     */
    public function take($id)
    {
        return view('takeDeposit', [
            'deposit' => $this->depositService->oneDeposit($id),
            'cards' => $this->cardService->getUserCards()
        ]);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return Application|Factory|View
     */
    public function details(Request $request, $id)
    {
        return view('depositInfo', [
            'cardFrom' => $this->cardService->getCardById(Arr::get($request, 'numberFrom', null))['number'],
            'deposit' => $this->depositService->oneDeposit($id),
            'currency' => Arr::get($request, 'currency', null),
            'duration' => Arr::get($request, 'duration', 0),
            'percent' => Arr::get($request, 'percents', 0),
            'sum' => Arr::get($request, 'sum', 0),
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
        $response = $this->depositService->accept($request, $id);
        if ($response[0] === 'success') {
            return redirect(route('user.takeDeposit', ['id' => $id]))->with('success', $response[1]);
        } else {
            return redirect(route('user.takeDeposit', ['id' => $id]))->withErrors(['error' => $response[1]]);
        }
    }

    /**
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function close($id)
    {
        $response = $this->depositService->close($id);
        if ($response[0] === 'success') {
            return redirect(route('user.allDeposits'))->with('success', $response[1]);
        } else {
            return redirect(route('user.allDeposits'))->withErrors(['error' => $response[1]]);
        }
    }
}
