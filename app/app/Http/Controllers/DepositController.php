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
use Illuminate\Support\Facades\Route;

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
            app()->getLocale(),
           'deposits' => $this->depositService->getBaseDeposits(),
           'yourDeposits' => $this->depositService->getUserDeposits()
        ]);
    }

    /**
     * @param integer $id
     * @return Application|Factory|View
     */
    public function take($lang, $id)
    {
        return view('takeDeposit', [
            app()->getLocale(),
            'deposit' => $this->depositService->oneDeposit($id),
            'cards' => $this->cardService->getUserCards()
        ]);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return Application|Factory|View
     */
    public function details(Request $request, $lang, $id)
    {
        $cardFrom = $this->cardService->getCardById(Arr::get($request, 'numberFrom', null));
        $cardNumber = Arr::get($cardFrom, 'number', null);
        return view('depositInfo', [
            app()->getLocale(),
            'cardFrom' => $cardNumber,
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
    public function accept(Request $request, $lang, $id)
    {
        $response = $this->depositService->accept($request, $id);
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.takeDeposit',
                [app()->getLocale(),'id' => $id]))->with('success', Arr::get($response, 1, null));
        } else {
            return redirect(route('user.takeDeposit',
                [app()->getLocale(),'id' => $id]))->withErrors(['error' => Arr::get($response, 1, null)]);
        }
    }

    /**
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function close($lang, $id)
    {
        $response = $this->depositService->close($id);
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.allDeposits', app()->getLocale()))->with('success', Arr::get($response, 1, null));
        } else {
            return redirect(route('user.allDeposits', app()->getLocale()))->withErrors(['error' => Arr::get($response, 1, null)]);
        }
    }
}
