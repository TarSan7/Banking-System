<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Services\CardService;
use App\Services\DepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * @var DepositService
     */
    private $depositService, $userCardRepository, $cardRepository, $activeDepositRepository;

    /**
     * @param DepositService $depositService
     */
    public function __construct(
        DepositService $depositService,
        UserCardRepository $userCardRepository,
        CardRepository $cardRepository,
        ActiveDepositRepository $activeDepositRepository
    ) {
        $this->depositService = $depositService;
        $this->userCardRepository = $userCardRepository;
        $this->cardRepository = $cardRepository;
        $this->activeDepositRepository = $activeDepositRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => "Unauthorized"
            ], 401);
        }
        return response()->json([
            'status' => 'success',
            'deposits' => $this->depositService->getBaseDeposits(),
            'yourDeposits' => $this->depositService->getUserDeposits()
        ]);
    }

    /**
     * @param Request $request
     * @param string $lang
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Request $request, $lang, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ]);
        }
        $arr = $this->userCardRepository->cardIdByUser(Auth::user()->id);
        foreach ($arr as $oneId) {
            $id = $this->cardRepository->getId(Arr::get($request, 'numberFrom', null));
            if (Arr::get($oneId, 'card_id', null) == $id) {
                $response = $this->depositService->accept($request, $id);
                return response()->json([
                    'response' => Arr::get($response, 1, null)
                ]);
            }
        }
        return response()->json([
            'error' => 'No such cards'
        ]);
    }

    /**
     * @param string $lang
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function close($lang, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized'
            ]);
        }
        $deposit = $this->activeDepositRepository->find($id);
        if ($deposit && Arr::get($deposit, 'user_id', null) == Auth::user()->id) {
            $response = $this->depositService->close($id);
            return response()->json([
                'response' => Arr::get($response, 1, null)
            ]);
        }
        return response()->json([
            'error' => 'No such deposit'
        ]);
    }
}
