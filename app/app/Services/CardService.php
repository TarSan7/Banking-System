<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Loan;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\LoanRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Eloquent\UserCardRepository;
use Database\Factories\CardFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\AssignOp\Mod;

class CardService
{
    /**
     * @var CardRepository
     * @var UserCardRepository
     * @var UserRepository
     */
    private $cardRepository, $userCardRepository, $userRepository, $card,
        $cardFactory, $transferRepository, $loanRepository, $allTransactionsService;

    /**
     * Responses for controller
     */
    const RESPONSES = array(
        'notExist' => 'This card doesn`t exist! Try again!',
        'used' => 'This card has already used!',
        'form' => 'An error occurred while saving data!',
        'done' => 'Done!'
    );

    /**
     * @param CardRepository $cardRepository
     * @param UserCardRepository $userCardRepository
     * @param UserRepository $userRepository
     * @param CardFactory $cardFactory
     * @param TransferRepository $transferRepository
     * @param LoanRepository $loanRepository
     */
    public function __construct(
        CardRepository $cardRepository,
        UserCardRepository $userCardRepository,
        UserRepository $userRepository,
        CardFactory $cardFactory,
        TransferRepository $transferRepository,
        LoanRepository $loanRepository,
        AllTransactionsService $allTransactionsService
    ) {
        $this->cardRepository = $cardRepository;
        $this->userCardRepository = $userCardRepository;
        $this->userRepository = $userRepository;
        $this->cardFactory = $cardFactory;
        $this->transferRepository = $transferRepository;
        $this->loanRepository = $loanRepository;
        $this->allTransactionsService = $allTransactionsService;
    }

    /**
     * Save card information
     * @param array $card
     */
    public function setCard($card)
    {
        $this->card = $card;
    }

    /**
     * Return model of card by it`s id
     * @param int $cardId
     * @return Model
     */
    public function getCardById($cardId): Model
    {
        return $this->cardRepository->find($cardId);
    }
    /**
     * Return model of card by it`s id
     * @param string $cardNum
     * @return Model|null
     */
    public function getCardByNum($cardNum): ?Model
    {
        return $this->cardRepository->getCardByNum($cardNum);
    }

    /**
     * Checking the card for existence
     * @return bool
     */
    public function cardExist($card = null): bool
    {
        return $this->cardRepository->cardExist($this->card);
    }

    /**
     * Checking if this card is in use
     * @return bool
     */
    public function cardAdded(): bool
    {
        $cardId = $this->cardRepository->getId(Arr::get($this->card, 'number', 0));
        return $this->userCardRepository->cardsExist($cardId);
    }

    /**
     * Creating card
     * @return bool
     */
    public function createCard($id = 0, $card_id = 0): bool
    {
        return (bool) $this->userCardRepository->create([
            'user_id' => Auth::user()->id ?? $id,
            'card_id' => $this->cardRepository->getId(Arr::get($this->card,'number', 0) ?? $card_id)
        ]) ?? false;
    }

    /**
     * Return all information about user cards
     * @return Collection
     */
    public function getUserCards($id = 1): Collection
    {
        $cardsId = $this->userRepository->getCards(Auth::user()->id ?? $id);
        return $this->cardRepository->findAll($cardsId);
    }

    public function transferInfo($number, $sum, $newSum, $currency): array
    {
        return array(
            'card_from' => 'Bank',
            'card_to' => $number,
            'date' => date('Y-m-d H:i:s'),
            'sum' => $sum,
            'new_sum' => $newSum,
            'currency' => $currency,
            'comment' => 'Loan money',
            'user_id' => Auth::user()->id ?? 0
        );
    }

    /**
     * @param float $sum
     * @param int $loanId
     * @return bool
     */
    public function newCreditCard($sum, $loanId): bool
    {
        $userCards = array();
        $allCardsId = $this->userCardRepository->cardIdByUser(Auth::user()->id ?? 0) ?? [];
        foreach ($allCardsId as $one) {
            $userCards[] = Arr::get($one, 'card_id', null);
        }
        $ifCard = $this->cardRepository->credit($userCards, $loanId);
        if ($ifCard) {
            $this->allTransactionsService->takeLoan($this->transferInfo(
                Arr::get($ifCard, 'number', null),
                $sum,
                Arr::get($ifCard, 'sum', null) + $sum,
                Arr::get($ifCard, 'currency', null)
            ), $ifCard, $sum, $loanId);
            return true;
        } else {
            $loan = $this->loanRepository->getLoan($loanId);
            $card = $this->cardFactory->createLoan(0, Arr::get($loan, 'currency', null));
            $this->cardRepository->create($card);
            $card = $this->getCardByNum(Arr::get($card, 'number', null));
            $this->userCardRepository->createNew(Auth::user()->id ?? 0, Arr::get($card, 'id', null));
            $this->allTransactionsService->takeLoan($this->transferInfo(
                Arr::get($card, 'number', null),
                $sum,
                $sum,
                Arr::get($card, 'currency', null)
            ), $card, $sum, $loanId);
            return true;
        }
    }

    /**
     * @return array
     */
    public function check(): array
    {
        if (!$this->cardExist()) {
            return ['error', Arr::get(self::RESPONSES, 'notExist', null)];
        } elseif ($this->cardAdded()) {
            return ['error', Arr::get(self::RESPONSES, 'used', null)];
        } elseif ($this->createCard()) {
            return ['success', Arr::get(self::RESPONSES, 'done', null)];
        } else {
            return ['error', Arr::get(self::RESPONSES, 'form', null)];
        }
    }
}
