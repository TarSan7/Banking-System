<?php

namespace App\Services;

use App\Models\ActiveDeposit;
use App\Models\ActiveLoan;
use App\Models\Loan;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\DepositRepository;
use App\Repository\Eloquent\LoanRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Repository\Eloquent\UserRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllTransactionsService
{
    /**
     * @var CardRepository
     * @var TransferRepository
     * @var UserCardRepository
     * @var LoanRepository
     * @var DepositRepository
     * @var ActiveDepositRepository
     * @var ActiveDeposit
     * @var ActiveLoan
     */
    private $cardRepository, $transferRepository, $userCardRepository, $loanRepository, $depositRepository;
    private $activeDepositRepository, $activeDepositModel, $activeLoanModel;

    /**
     * @param ActiveLoan $activeLoan
     * @param ActiveDeposit $activeDeposit
     * @param CardRepository $cardRepository
     * @param TransferRepository $transferRepository
     * @param UserCardRepository $userCardRepository
     * @param LoanRepository $loanRepository
     * @param DepositRepository $depositRepository
     * @param ActiveDepositRepository $activeDepositRepository
     */
    public function __construct(
        ActiveLoan $activeLoan,
        ActiveDeposit $activeDeposit,
        CardRepository $cardRepository,
        TransferRepository $transferRepository,
        UserCardRepository $userCardRepository,
        LoanRepository $loanRepository,
        DepositRepository $depositRepository,
        ActiveDepositRepository $activeDepositRepository
    ) {
        $this->activeLoanModel = $activeLoan;
        $this->activeDepositModel = $activeDeposit;
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
        $this->userCardRepository = $userCardRepository;
        $this->loanRepository = $loanRepository;
        $this->depositRepository = $depositRepository;
        $this->activeDepositRepository = $activeDepositRepository;
    }

    /**
     * Making card transfer
     * @param array $transArray
     * @param array $infoFrom
     * @param array $infoTo
     * @throws Exception
     */
    public function make($transArray, $infoFrom, $infoTo)
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

    /**
     * Taking a loan
     * @param array $transInfo
     * @param array $card
     * @param float $sum
     * @param float $bankSum
     * @param int $id
     * @throws Exception
     */
    public function takeLoan($transInfo, $card, $sum, $bankSum, $id)
    {
        DB::beginTransaction();
        try {
            $cardId = Arr::get($card, 'id', null);
            $this->transferRepository->create($transInfo);
            $this->cardRepository->updateSum($cardId, -$sum);
            $card = $this->cardRepository->find($cardId);
            $this->cardRepository->updateGeneral(Arr::get($card, 'currency', null), ['sum' => $bankSum - $sum]);
            $this->loanRepository->newLoan(
                $id,
                $sum,
                $cardId,
                Auth::user()->id ?? 0
            );
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Creating new card
     * @param array $card
     * @throws Exception
     */
    public function cardCreate($card)
    {
        DB::beginTransaction();
        try {
            $this->cardRepository->create($card);
            $card['id'] = $this->cardRepository->getId(Arr::get($card, 'number', null));
            $this->userCardRepository->createNew(Auth::user()->id ?? 0, Arr::get($card, 'id', null));
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Taking a deposit
     * @param array $transInfo
     * @param array $deposit
     * @param int $id
     * @param string $currency
     * @param float $sum
     * @throws Exception
     */
    public function takeDeposit($transInfo, $deposit, $id, $currency, $sum)
    {
        DB::beginTransaction();
        try {
            $this->transferRepository->create($transInfo);
            $bankSum = $this->cardRepository->generalSumByCurrency($currency);
            $this->cardRepository->updateGeneral($currency, ['sum' => $bankSum + $sum]);
            $this->cardRepository->updateSum($deposit['numberFrom'], $sum);

            $this->depositRepository->newDeposit(
                $id,
                $deposit,
                Auth::id() ?? 0
            );
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Closing a deposit
     * @param int $id
     * @param array $deposit
     * @throws Exception
     */
    public function closeDeposit($id, $deposit = null)
    {
        DB::beginTransaction();
        try {
            $this->activeDepositRepository->getMoney($id);
            if ($deposit !== null) {
                $this->transferRepository->create([
                    'card_from' => 'Bank',
                    'card_to' => $this->cardRepository->getNumber(Arr::get($deposit, 'card_id', null)),
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => Arr::get($deposit, 'total_sum', null),
                    'new_sum' => Arr::get($deposit, 'total_sum', null),
                    'currency' => Arr::get($deposit, 'currency', null),
                    'comment' => 'Closing deposit',
                    'user_id' => Auth::user()->id ?? 0
                ]);
            }
            $this->activeDepositRepository->delete($id);
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Deposit decrease
     * @param int $id
     * @param int $monthLeft
     * @param float $monthSum
     * @param array $deposit
     * @throws Exception
     */
    public function depositDecrease($id, $monthLeft, $monthSum, $deposit)
    {
        DB::beginTransaction();
        try {
            $this->activeDepositModel->where('id', $id)->update([
                'month_left' => $monthLeft - 1,
                'total_sum' => Arr::get($deposit, 'total_sum', null) + $monthSum
            ]);
            $this->transferRepository->create([
                'card_from' => 'Bank',
                'card_to' => 'Deposit',
                'date' => date('Y-m-d H:i:s'),
                'sum' => $monthSum,
                'new_sum' => $monthSum,
                'currency' => Arr::get($deposit, 'currency', null),
                'comment' => 'Percents to deposit',
                'user_id' => Arr::get($deposit, 'user_id', null)
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Loan decrease
     * @param array $loan
     * @param int $monthLeft
     * @param int $loanId
     * @throws Exception
     */
    public function decreaseLoan($loan, $monthLeft, $loanId)
    {
        DB::beginTransaction();
        try {
            $cardFrom = $this->cardRepository->find(Arr::get($loan, 'card_id', null));
            $bankCurrency = Arr::get($cardFrom, 'currency', null);
            $bankSum = $this->cardRepository->generalSumByCurrency($bankCurrency);
            $monthSum = Arr::get($loan, 'month_pay', null);

            $this->activeLoanModel->where('id', $loanId)->update(['month_left' => $monthLeft-1]);
            $this->cardRepository->updateSum(Arr::get($loan, 'card_id', null), $monthSum);
            $this->cardRepository->updateGeneral($bankCurrency, ['sum' => $bankSum + $monthSum]);
            $this->transferRepository->create([
                'card_from' => Arr::get($cardFrom, 'id', null),
                'card_to' => 'Bank',
                'date' => date('Y-m-d H:i:s'),
                'sum' => $monthSum,
                'new_sum' => $monthSum,
                'currency' => Arr::get($cardFrom, 'currency', null),
                'comment' => 'Loan decrease',
                'user_id' => Arr::get($loan, 'user_id', null)
            ]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }
}
