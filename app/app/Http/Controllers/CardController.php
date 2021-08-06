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
    public function __construct(
        TransferService $transferService,
        CardService $cardService
    ) {
        $this->transferService = $transferService;
        $this->cardService = $cardService;
    }

    /**
     * @param AddCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function addCard(AddCardRequest $request)
    {
        $this->cardService->setCard($request->validated());

        if (!$this->cardService->cardExist()) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card doesn`t exist! Try again!'
            ]);
        }

        if ($this->cardService->cardAdded()) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card has already used!'
            ]);
        }

        if ($this->cardService->createCard()) {
            return redirect(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
            'formError' => 'An error occurred while saving data!'
        ]);
    }

    /**
     * @param int $cardId
     * @return Application|Factory|View
     */
    public function cardInfo($cardId)
    {
        return view('oneCard', [
            'card' => $this->cardService->getCardById($cardId),
            'transactions' => $this->transferService->getCardTransfers($cardId)
        ]);
    }
}
