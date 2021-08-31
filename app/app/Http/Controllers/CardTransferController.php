<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToCardRequest;
use App\Services\CardService;
use App\Services\TransferService;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class CardTransferController
 * @package App\Http\Controllers
 */
class CardTransferController extends Controller
{
    /**
     * @var TransferService
     * @var CardService
     */
    private $transferService, $cardService;

    /**
     * @param TransferService $transferService
     * @param CardService $cardService
     */
    public function __construct(TransferService $transferService, CardService $cardService)
    {
        $this->transferService = $transferService;
        $this->cardService = $cardService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('cardTransfer', [app()->getLocale(), 'cards' => $this->cardService->getUserCards()]);
    }

    /**
     * @param TransferToCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function make(TransferToCardRequest $request)
    {
        $this->transferService->setInfo($request->validated());
        $response = $this->transferService->cardCheck();
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.cardTransfer', app()->getLocale()))->with('success', Arr::get($response, 1, null));
        } else {
            return redirect(route('user.cardTransfer', app()->getLocale()))->withErrors(['error' => Arr::get($response, 1, null)]);
        }
    }

}
