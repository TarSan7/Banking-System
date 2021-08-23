<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Services\CardService;
use App\Services\TransferService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\View\View;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
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
     * @param AddCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function add(AddCardRequest $request)
    {
        $this->cardService->setCard($request->validated());
        $response = $this->cardService->check();
        if (Arr::get($response, 0, null) === 'success') {
            return redirect(route('user.addCard'))->with('success', Arr::get($response, 1, null));
        } else {
            return redirect(route('user.addCard'))->withErrors(['error' => Arr::get($response, 1, null)]);
        }
    }

    /**
     * @param int $cardId
     * @return Application|Factory|View
     */
    public function info($cardId)
    {
        return view('oneCard', [
            'card' => $this->cardService->getCardById($cardId),
            'transactions' => $this->transferService->getCardTransfers($cardId)
        ]);
    }
}
