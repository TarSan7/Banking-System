<?php

namespace App\Services;

use App\Models\Card;
use App\Repository\Eloquent\CardRepository;
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
    private $cardRepository, $userCardRepository, $userRepository, $card, $cardFactory;

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
     * @param UserRepository $transferRepository
     * @param CardFactory $cardFactory
     */
    public function __construct(
        CardRepository $cardRepository,
        UserCardRepository $userCardRepository,
        UserRepository $userRepository,
        CardFactory $cardFactory
    ) {
        $this->cardRepository = $cardRepository;
        $this->userCardRepository = $userCardRepository;
        $this->userRepository = $userRepository;
        $this->cardFactory = $cardFactory;
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
    public function cardExist(): bool
    {
        return $this->cardRepository->cardExist($this->card);
    }

    /**
     * Checking if this card is in use
     * @return bool
     */
    public function cardAdded(): bool
    {
        $cardId = $this->cardRepository->getId(Arr::get($this->card, 'number', null));
        return $this->userCardRepository->cards($cardId);
    }

    /**
     * Creating card
     * @return bool
     */
    public function createCard(): bool
    {
        return (bool) $this->userCardRepository->create([
            'user_id' => Auth::user()->id,
            'card_id' => $this->cardRepository->getId(Arr::get($this->card,'number', null))
        ]) ?? false;
    }

    /**
     * Return all information about user cards
     * @return Collection
     */
    public function getUserCards(): Collection
    {
        $cardsId = $this->userRepository->getCards(Auth::user()->id);
        return $this->cardRepository->findAll($cardsId);
    }

    /**
     * @param float $sum
     * @param int $loanId
     * @return Model|null
     */
    public function newCard($sum, $loanId): ?Model
    {
        $card = $this->cardFactory->createLoan($sum, $this->cardRepository->getCurrencyFrom($loanId));
        $this->cardRepository->create($card);
        $card = $this->getCardByNum($card['number']);
        $this->userCardRepository->createNew(Auth::user()->id, $card['id']);
        return $card ?? null;
    }

    /**
     * @return array
     */
    public function check(): array
    {
        if (!$this->cardExist()) {
            return ['error', self::RESPONSES['notExist']];
        } elseif ($this->cardAdded()) {
            return ['error', self::RESPONSES['used']];
        } elseif ($this->createCard()) {
            return ['success', self::RESPONSES['done']];
        } else {
            return ['error', self::RESPONSES['form']];
        }
    }
}
