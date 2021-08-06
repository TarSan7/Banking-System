<?php

namespace App\Services;

use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Eloquent\UserCardRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CardService
{
    /**
     * @var CardRepository
     * @var UserCardRepository
     * @var UserRepository
     */
    private $cardRepository, $userCardRepository, $userRepository, $card;

    /**
     * @param CardRepository $cardRepository
     * @param UserCardRepository $userCardRepository
     * @param UserRepository $transferRepository
     */
    public function __construct(
        CardRepository $cardRepository,
        UserCardRepository $userCardRepository,
        UserRepository $userRepository
    ) {
        $this->cardRepository = $cardRepository;
        $this->userCardRepository = $userCardRepository;
        $this->userRepository = $userRepository;
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
}
