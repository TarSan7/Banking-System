<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCardRequest;
use App\Models\Card;
use App\Models\User;
use App\Models\UserCard;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Repository\Eloquent\UserRepository;
use App\Services\CardService;
use App\Services\TransferService;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    private $userRepository, $cardRepository, $userCardRepository, $cardService,
            $transferService;

    public function __construct(
        UserRepository $userRepository,
        CardRepository $cardRepository,
        UserCardRepository $userCardRepository,
        CardService $cardService,
        TransferService $transferService
    ) {
        $this->userRepository = $userRepository;
        $this->cardRepository= $cardRepository;
        $this->userCardRepository = $userCardRepository;
        $this->cardService = $cardService;
        $this->transferService = $transferService;
    }

    public function userCards()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized'
            ]);
        }
        $cardsId = $this->userCardRepository->cardIdByUser($user->id);
        return response()->json([
            'username' => $this->userRepository->getUsername($user->email),
            'cards' => $this->cardRepository->findAll($cardsId)
        ]);
    }

    public function addCard(Request $request)
    {

        if (!Auth::check()) {
            return response()->json([
                'error' => "Unauthorized"
            ], 401);
        }
        $validate = Validator::make($request->all(), [
            'number' => 'required|exists:cards,number|regex:/^[0-9]{13,16}$/',
            'cvv' => 'required|regex:/^[0-9]{3}$/',
            'expires-end' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }
        $this->cardService->setCard($validate->validated());
        $response = $this->cardService->check();
        if (Arr::get($response, 0, null) === 'success') {
            return response()->json([
                'success' => "Card was added"
            ]);
        }
        return response()->json([
            'error' => $response[1]
        ]);
    }

    public function info($lang, $cardId)
    {
        $arr = $this->userCardRepository->cardIdByUser(Auth::user()->id);
        foreach ($arr as $oneId) {
            if (Arr::get($oneId, 'card_id', null) == $cardId) {
                return response()->json([
                    'card' => $this->cardService->getCardById($cardId),
                    'transactions' => $this->transferService->getCardTransfers($cardId)
                ]);
            }
        }
        return response()->json([
            'error' => 'No such cards'
        ]);

    }
}
