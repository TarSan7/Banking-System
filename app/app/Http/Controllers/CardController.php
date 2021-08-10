<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Services\CardService;
use App\Services\TransferService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
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
    public function __construct(TransferService $transferService, CardService $cardService
    ) {
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
        if ($response[0] === 'success') {
            return redirect(route('user.addCard'))->with('success', $response[1]);
        } else {
            return redirect(route('user.addCard'))->withErrors(['error' => $response[1]]);
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
