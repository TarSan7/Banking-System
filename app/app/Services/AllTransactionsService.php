<?php

namespace App\Services;

use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AllTransactionsService
{
    /**
     * @var CardRepository
     * @var TransferRepository
     * @var UserRepository
     */
    private $cardRepository, $transferRepository, $loanService, $depositService;

    /**
     * @param CardRepository $cardRepository
     * @param TransferRepository $transferRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        CardRepository $cardRepository,
        TransferRepository $transferRepository,
        LoanService $loanService,
        DepositService $depositService
    ) {
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
        $this->loanService = $loanService;
        $this->depositService = $depositService;
    }


    public function make($transArray, $infoFrom, $infoTo, $type = null)
    {
        DB::beginTransaction();
        try {
            $this->transferRepository->create($transArray);
            $this->cardRepository->updateFrom(
                Arr::get($infoFrom, 'number', null),
                ['sum' => Arr::get($infoFrom, 'sum', null)]
            );
            $this->cardRepository->updateTo(
                Arr::get($infoTo, 'number', null),
                ['sum' => Arr::get($infoTo, 'sum', null)]
            );
        }  catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    public function takeLoan($transInfo, $card, $sum, $id)
    {
        DB::beginTransaction();
        try {
//            $this->transferRepository->create($transInfo);
//            $this->cardRepository->updateSum(Arr::get($card, 'id', null), -$sum);
//            $bankSum = $this->cardRepository->generalSumByCurrency(Arr::get($card, 'currency', null));
//            $this->cardRepository->updateGeneral(Arr::get($card, 'currency', null), ['sum' => $bankSum - $sum]);
//            $this->loanService->newLoan($card, $id);
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    public function takeDeposit($transInfo, $deposit, $id, $currency, $sum, $numberFrom)
    {
        DB::beginTransaction();
        try {
//            $this->transferRepository->create($transInfo);
////            $this->depositService->newDeposit($deposit, $id);
//            $bankSum = $this->cardRepository->generalSumByCurrency($currency);
//            $this->cardRepository->updateGeneral($currency, ['sum' => $bankSum + $sum]);
//            $this->cardRepository->updateSum($numberFrom, $sum);
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }
}
