<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferToCardRequest;
use App\Repository\Eloquent\UserCardRepository;
use App\Services\TransferService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CardTransferController extends Controller
{
    /**
     * @var TransferService
     * @var UserCardRepository
     */
    private $transferService, $userCardRepository;

    /**
     * @param TransferService $transferService
     * @param UserCardRepository $userCardRepository
     */
    public function __construct(
        TransferService $transferService,
        UserCardRepository $userCardRepository
    ) {
        $this->transferService = $transferService;
        $this->userCardRepository = $userCardRepository;
    }

    /**
     * @param TransferToCardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function make(TransferToCardRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ]);
        }
        $cards = $this->userCardRepository->cardIdByUser(Auth::user()->id);
        foreach ($cards as $card) {
            if (Arr::get($card, 'card_id', null) == Arr::get($request, 'numberFrom', null)) {
                $this->transferService->setInfo($request->validated());
                $response = $this->transferService->cardCheck();
                return response()->json([
                    'response' => Arr::get($response, 0, null),
                    'message' => Arr::get($response, 1, null)
                ]);
            }
        }
        return response()->json([
            'error' => 'No such cards'
        ]);
    }

    public function all()
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ]);
        }

        return response()->json([
            'response' => 'success',
            'transactions' => $this->transferService->getTransactions()
        ]);
    }
}
