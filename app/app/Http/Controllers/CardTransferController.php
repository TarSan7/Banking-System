<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToCardRequest;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class CardTransferController
 * @package App\Http\Controllers
 */
class CardTransferController extends Controller
{
    /**
     * @var CardRepository
     * @var TransferRepository
     * @var UserRepository
     */
    private $cardRepository, $transferRepository, $userRepository;

    /**
     * @param CardRepository $cardRepository
     * @param TransferRepository $transferRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        CardRepository $cardRepository,
        TransferRepository $transferRepository,
        UserRepository $userRepository
    ) {
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function goTransfer() {
        $cards = $this->cardRepository->findAll($this->userRepository->getCards(Auth::user()->id));
        return view('cardTransfer', ['cards' => $cards]);
    }

    /**
     * @param TransferToCardRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function transferToCard(TransferToCardRequest $request)
    {
        $validate = $request->validated();
        $sumFrom = $this->cardRepository->getSumFrom(Arr::get($validate, 'numberFrom', null));
        $sumTo = $this->cardRepository->getSumTo(Arr::get($validate, 'numberTo', null));

        if (Arr::get($validate, 'sum', null) > $sumFrom) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }
        if ($this->cardRepository->getCurrencyFrom(Arr::get($validate, 'numberFrom', null)) !=
            $this->cardRepository->getCurrencyTo(Arr::get($validate, 'numberTo', null))) {
            return redirect(route('user.cardTransfer'))->withErrors([
                'sum' => 'Different currencies! Try another card.'
            ]);
        }

        $tranFrom = $this->transferRepository->create([
            'card_from' => $this->cardRepository->find(Arr::get($validate, 'numberFrom', null))['number'],
            'card_to' => Arr::get($validate, 'numberTo', null),
            'date' => date('Y-m-d H:i:s'),
            'sum' => Arr::get($validate, 'sum', null),
            'new_sum' => $sumFrom - Arr::get($validate, 'sum', null),
            'currency' => $this->cardRepository->getCurrencyFrom(Arr::get($validate, 'numberFrom', null)),
            'comment' => Arr::get($validate, 'comment', null)
        ]);

        $this->cardRepository->updateFrom(Arr::get($validate, 'numberFrom', null),
            ['sum' => $sumFrom - Arr::get($validate, 'sum', null)]);
        $this->cardRepository->updateTo(Arr::get($validate, 'numberTo', null),
            ['sum' => $sumTo + Arr::get($validate, 'sum', null)]);

        if ($tranFrom) {
            return redirect(route('user.cardTransfer'))->with('success', 'Done!');
        }

        return redirect(route('user.cardTransfer'))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }
}
