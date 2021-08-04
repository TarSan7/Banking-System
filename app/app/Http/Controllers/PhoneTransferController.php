<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferToPhoneRequest;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 * Class PhoneTransferController
 * @package App\Http\Controllers
 */
class PhoneTransferController extends Controller
{
    /**
     * @var UserRepository
     * @var TransferRepository
     * @var CardRepository
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
     * @param $id
     * @return Application|Factory|View
     */
    public function goTransfer($id)
    {
        $cards = $this->cardRepository->findAll($this->userRepository->getCards(Auth::user()->id));
        return view('phoneTransfer', ['cards' => $cards, 'id' => $id]);
    }

    /**
     * @param TransferToPhoneRequest $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function transferToPhone(TransferToPhoneRequest $request, $id)
    {
        $validate = $request->validated();
        if ($id == 'phone' && !preg_match('/^(((\+380)[0-9]{9})?|([A-Za-z]{0,}))$/', $validate['numberTo'])) {
            return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
                'numberTo' => 'Incorrect number format!'
            ]);
        }

        $sumFrom = $this->cardRepository->getSumFrom($validate['numberFrom']);

        if (Arr::get($validate, 'sum', null) > $sumFrom) {
            return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
                'sum' => 'Not enough resource!'
            ]);
        }

        $numberTo = $this->cardRepository->getGeneralCardNum(Arr::get($validate, 'numberFrom', null));

        $tranFrom = $this->transferRepository->create([
            'card_from' => $this->cardRepository->find(Arr::get($validate, 'numberFrom', null))['number'],
            'card_to' => $numberTo,
            'date' => date('Y-m-d H:i:s'),
            'sum' => Arr::get($validate, 'sum', null),
            'new_sum' => $sumFrom - Arr::get($validate, 'sum', null),
            'currency' => $this->cardRepository->getCurrencyFrom(Arr::get($validate, 'numberFrom', null)),
            'comment' => Arr::get($validate, 'comment', null)
                ."To: ".Arr::get($validate, 'numberTo', null)
        ]);

        $this->cardRepository->updateFrom(Arr::get($validate, 'numberFrom', null),
            ['sum' => $sumFrom - Arr::get($validate, 'sum', null)]);
        $this->cardRepository->updateTo($numberTo,
            ['sum' => $this->cardRepository->getSumTo($numberTo) + Arr::get($validate, 'sum', null)]);


        if ($tranFrom) {
            return redirect(route('user.phoneTransfer', ['id'=>$id]))->with('success', 'Done!');
        }

        return redirect(route('user.phoneTransfer', ['id' => $id]))->withErrors([
            'formError' => 'An error occurred while transfer.'
        ]);
    }
}
