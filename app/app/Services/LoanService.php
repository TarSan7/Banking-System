<?php

namespace App\Services;

use App\Repository\Eloquent\LoanRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class LoanService
{
    /**
     * @var LoanRepository
     */
    private $loanRepository , $cardService;

    /**
     * Responses for controller
     */
    const RESPONSES = array(
        'form' => 'An error occurred!',
        'done' => 'Done!'
    );

    /**
     * @param LoanRepository $loanRepository
     * @param CardService $cardService
     */
    public function __construct(LoanRepository $loanRepository, CardService $cardService)
    {
        $this->loanRepository = $loanRepository;
        $this->cardService = $cardService;
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
        return (bool) $this->loanRepository
            ->newLoan($id, Arr::get($card, 'sum', null), Arr::get($card, 'id', null)) ?? false;
    }

    /**
     * @param float $sum
     * @param integer $id
     * @return array
     */
    public function accept($sum, $id): array
    {
        $card = $this->cardService->newCard($sum, $id);
        if (!$card) {
           return ['error', self::RESPONSES['form']];
        } elseif ($this->newLoan($card, $id)) {
            return ['success', self::RESPONSES['done']];
        } else {
            return ['error', self::RESPONSES['form']];
        }
    }
}
