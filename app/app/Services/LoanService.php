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
    private $loanRepository, $cardRepository, $cardService, $activeLoanRepository;

    /**
     * Responses for controller
     */
    const RESPONSES = array(
        'form' => 'An error occurred!',
        'done' => 'Done!',
        'tooMuch' => 'Too much loans for one User!'
    );

    /**
     * @param LoanRepository $loanRepository
     * @param CardService $cardService
     */
    public function __construct(
        LoanRepository $loanRepository,
        CardService $cardService,
        ActiveLoanRepository $activeLoanRepository,
        CardRepository $cardRepository
    ) {
        $this->loanRepository = $loanRepository;
        $this->cardService = $cardService;
        $this->activeLoanRepository = $activeLoanRepository;
        $this->cardRepository = $cardRepository;
    }

//    /**
//     * @return int
//     */
//    public function userId(): int
//    {
//        return Auth::user()->id;
//    }

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
        return $this->loanRepository
            ->newLoan($id, Arr::get($card, 'sum', null), Arr::get($card, 'id', null), Auth::id()) ?? false;
    }

    /**
     * @param float $sum
     * @param integer $id
     * @return array
     */
    public function accept($sum, $id): array
    {
        if ($this->countUserLoans() < 3) {
            $card = $this->cardService->newCreditCard($sum, $id);
            if (!$card) {
                return ['error', self::RESPONSES['form']];
            } elseif ($this->newLoan($card, $id)) {
                return ['success', self::RESPONSES['done']];
            } else {
                return ['error', self::RESPONSES['form']];
            }
        } else {
            return ['error', self::RESPONSES['tooMuch']];
        }
    }

    /**
     * @return int
     */
    public function countUserLoans(): int
    {
        return count($this->activeLoanRepository->userLoans(Auth::user()->id));
    }

    /**
     * @return array
     */
    public function getUserLoans(): array
    {
        $loans = $this->activeLoanRepository->userLoans(Auth::user()->id);
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
