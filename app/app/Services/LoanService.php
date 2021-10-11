<?php

namespace App\Services;

use App\Repository\Eloquent\ActiveLoanRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\LoanRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LoanService
{
    /**
     * @var LoanRepository
     * @var CardRepository
     * @var CardService
     * @var ActiveLoanRepository
     * @var AllTransactionsService
     */
    private $loanRepository, $cardRepository, $cardService, $activeLoanRepository, $allTransactionsService;

    const MAX_LOANS = 3;

    /**
     * Responses for controller
     */
    const RESPONSES = array(
        'form' => 'An error occurred!',
        'done' => 'Done!',
        'tooMuch' => 'Too much loans for one User!',
        'money' => 'Bank doesn`t have so much money'
    );

    /**
     * @param LoanRepository $loanRepository
     * @param CardService $cardService
     * @param ActiveLoanRepository $activeLoanRepository
     * @param CardRepository $cardRepository
     * @param AllTransactionsService $allTransactionsService
     */
    public function __construct(
        LoanRepository $loanRepository,
        CardService $cardService,
        ActiveLoanRepository $activeLoanRepository,
        CardRepository $cardRepository,
        AllTransactionsService $allTransactionsService
    ) {
        $this->loanRepository = $loanRepository;
        $this->cardService = $cardService;
        $this->activeLoanRepository = $activeLoanRepository;
        $this->cardRepository = $cardRepository;
        $this->allTransactionsService = $allTransactionsService;
    }

    /**
     * Get all existing loans
     * @return Collection|null
     */
    public function getBaseLoans(): ?Collection
    {
        return $this->loanRepository->all();
    }

    /**
     * Getting one loan by id
     * @param $id
     * @return Model|null
     */
    public function oneLoan($id): ?Model
    {
        return $this->loanRepository->find($id);
    }

    /**
     * Accepting loan
     * @param float $sum
     * @param integer $id
     * @return array
     */
    public function accept($sum, $id): array
    {
        $loanCurr = $this->loanRepository->getCurrency($id);
        $countLoans = $this->countUserLoans();
        if ($countLoans < self::MAX_LOANS && $this->cardRepository->checkGeneralSum($sum, $loanCurr)) {
            $this->cardService->newCreditCard($sum, $id);
            return ['success', Arr::get(self::RESPONSES, 'done', null)];
        } else {
            if ($countLoans >= self::MAX_LOANS) {
                return ['error', Arr::get(self::RESPONSES, 'tooMuch', null)];
            }
            return ['error', Arr::get(self::RESPONSES, 'money', null)];
        }
    }

    /**
     * Counting user loans
     * @return int
     */
    public function countUserLoans(): int
    {
        return count($this->activeLoanRepository->userLoans(Auth::user()->id ?? 0));
    }

    /**
     * Decreasing sum of loan
     * @return bool
     */
    public function decrease($loans): bool
    {

        foreach ($loans as $loan) {
            $loanId = Arr::get($loan, 'id', null);
            $monthLeft = Arr::get($loan, 'month_left', null);
            $this->allTransactionsService->decreaseLoan($loan, $monthLeft, $loanId);
            if ($monthLeft <= 0) {
                $this->activeLoanRepository->delete($loanId);
            }
        }
        return true;
    }


    /**
     * Getting all user loans
     * @return array
     */
    public function getUserLoans(): array
    {
        $loans = $this->activeLoanRepository->userLoans(Auth::user()->id ?? 0);
        $rez = array();
        foreach ($loans as $loan) {
            $base = $this->loanRepository->getLoan(Arr::get($loan, 'loan_id', null));
            $rez[] = array(
                'title' => Arr::get($base, 'title', null),
                'percent' => Arr::get($base, 'percent', null),
                'duration' => Arr::get($base, 'duration', null),
                'currency' => Arr::get($base, 'currency', null),
                'total-sum' => Arr::get($loan, 'total_sum', 0),
                'month-pay' => Arr::get($loan, 'month_pay', null),
                'month-left' => Arr::get($loan, 'month_left', null),
                'card-number' => $this->cardRepository->getNumber(Arr::get($loan, 'card_id', null))
            );
        }
        return $rez;
    }
}
