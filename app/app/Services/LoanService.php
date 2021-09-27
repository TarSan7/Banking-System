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
     */
    private $loanRepository, $cardRepository, $cardService, $activeLoanRepository, $allTransactionsService;

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
     * @param $id
     * @return Model|null
     */
    public function oneLoan($id): ?Model
    {
        return $this->loanRepository->find($id);
    }

    /**
     * @param array $card
     * @param integer $id
     * @return bool
     */
    public function newLoan($card, $id): bool
    {
        return $this->loanRepository->newLoan(
            $id,
            Arr::get($card, 'sum', null),
            Arr::get($card, 'id', null),
            Auth::user()->id ?? 0,
        ) ?? false;
    }

    /**
     * @param float $sum
     * @param integer $id
     * @return array
     */
    public function accept($sum, $id): array
    {
        $loanCurr = $this->loanRepository->getCurrency($id);
        if ($this->countUserLoans() < 3 && $this->cardRepository->checkGeneralSum($sum, $loanCurr)) {
            $this->cardService->newCreditCard($sum, $id);
            return ['success', Arr::get(self::RESPONSES, 'done', null)];
        } else {
            return ['error', Arr::get(self::RESPONSES, 'tooMuch', null)];
        }
    }

    /**
     * @return int
     */
    public function countUserLoans(): int
    {
        return count($this->activeLoanRepository->userLoans(Auth::user()->id ?? 0));
    }

    /**
     * @return bool
     */
    public function decrease(): bool
    {
        $loans = $this->activeLoanRepository->getLoansByDate();

        foreach ($loans as $loan) {
            $loanId = Arr::get($loan, 'id', null);
            $monthLeft = Arr::get($loan, 'month_left', null);

            $time1 = microtime(TRUE);

            $this->allTransactionsService->decreaseLoan($loan, $monthLeft, $loanId);

            $time2 = microtime(TRUE);
            $time = $time2 - $time1;
            file_put_contents('debug.txt', "\n\n Updating time: " . $time, FILE_APPEND);
            if ($monthLeft <= 0) {
                $time1 = microtime(TRUE);

                $this->activeLoanRepository->delete($loanId);

                $time2 = microtime(TRUE);
                $time = $time2 - $time1;
                file_put_contents('debug.txt', "\n\n Deleting time: " . $time, FILE_APPEND);
            }
        }
        return true;
    }


    /**
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
