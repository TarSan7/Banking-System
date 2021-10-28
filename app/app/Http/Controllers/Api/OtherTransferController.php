<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferToPhoneRequest;
use App\Repository\Eloquent\UserCardRepository;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class OtherTransferController extends Controller
{
    private $transferService, $userCardRepository;

    public function __construct(
        TransferService $transferService,
        UserCardRepository $userCardRepository
    ) {
        $this->transferService = $transferService;
        $this->userCardRepository = $userCardRepository;
    }

    public function make(TransferToPhoneRequest $request, $lang, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
        $cards = $this->userCardRepository->cardIdByUser(Auth::user()->id);
        foreach ($cards as $card) {
            if (Arr::get($card, 'card_id', null) == Arr::get($request, 'numberFrom', null)) {
                $this->transferService->setInfo($request->validated(), $id);
                $response = $this->transferService->otherCheck($id);
                return response()->json([
                    'response' => Arr::get($response, 0, null),
                    'message' => Arr::get($response, 1, null)
                ], 200);
            }
        }
        return response()->json([
            'error' => 'No such cards'
        ]);
    }
}
