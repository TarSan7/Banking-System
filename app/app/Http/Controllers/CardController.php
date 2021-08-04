<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCardRequest;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserCardRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class CardController
 * @package App\Http\Controllers
 */
class CardController extends Controller
{
    /**
     * @var CardRepository
     * @var UserCardRepository
     * @var TransferRepository
     */
    private $cardRepository, $userCardRepository, $transferRepository;

    /**
     * @param CardRepository $cardRepository
     * @param UserCardRepository $userCardRepository
     * @param TransferRepository $transferRepository
     */
    public function __construct(
        CardRepository $cardRepository,
        UserCardRepository $userCardRepository,
        TransferRepository $transferRepository
    ) {
        $this->cardRepository = $cardRepository;
        $this->userCardRepository = $userCardRepository;
        $this->transferRepository = $transferRepository;
    }

    /**
     * @param AddCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function addCard(AddCardRequest $request)
    {
        $validate = $request->validated();

        if (!$this->cardRepository->cardExist($validate)) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card doesn`t exist! Try again!'
            ]);
        }

        if ($this->userCardRepository->cards($this->cardRepository->getId(Arr::get($validate, 'number', null)))) {
            return redirect(route('user.addCard'))->withErrors([
                'number' => 'This card has already used!'
            ]);
        }

        $uCard = $this->userCardRepository->create(['user_id' => Auth::user()->id,
                 'card_id' => $this->cardRepository->getId(Arr::get($validate,'number', null))]);
        if ($uCard) {
            return redirect(route('user.private'));
        }

        return redirect(route('user.login'))->withErrors([
            'formError' => 'An error occurred while saving data!'
        ]);
    }

    /**
     * @param $cardId
     * @return Application|Factory|View
     */
    public function cardInfo($cardId)
    {
        $card = $this->cardRepository->find($cardId);
        return view('oneCard', ['card' => $card,
            'transactions' => $this->transferRepository->getCardTransactions(Arr::get($card, 'number', null))]);
    }
}
