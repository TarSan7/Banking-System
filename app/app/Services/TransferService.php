<?php

namespace App\Services;

use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransferService
{
    /**
     * @var CardRepository
     * @var TransferRepository
     * @var array
     * @var string
     */
    private $cardRepository, $transferRepository, $tranInfo, $phoneNumber;

    /**
     * @param CardRepository $cardRepository
     * @param TransferRepository $transferRepository
     */
    public function __construct(
        CardRepository $cardRepository,
        TransferRepository $transferRepository
    ) {
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
    }

    /**
     * Saving information about transfer
     * @param array $info
     * @param string|null $id
     */
    public function setInfo($info, $id = null)
    {
        $this->tranInfo = $info;
        if ($id) {
            $this->phoneNumber = Arr::get($this->tranInfo, 'numberTo', null);
            $this->tranInfo['comment'] .= "To: " . Arr::get($this->tranInfo, 'numberTo', null);
            $this->tranInfo['numberTo'] = $this->cardRepository
                ->getGeneralCardNum(Arr::get($this->tranInfo, 'numberFrom', null));
        }
    }

    /**
     * Return balance of card from which make transaction
     * @return float
     */
    public function getBalanceFrom(): float
    {
        return $this->cardRepository->getSumFrom(Arr::get($this->tranInfo, 'numberFrom', null));
    }

    /**
     * Return balance of card to which make transaction
     * @return float
     */
    public function getBalanceTo(): float
    {
        return $this->cardRepository->getSumTo(Arr::get($this->tranInfo, 'numberTo', null));
    }

    /**
     * Return sum of transfer
     * @return float
     */
    public function transferSum(): float
    {
        return Arr::get($this->tranInfo, 'sum', null);
    }

    /**
     * Comparing currency of cards
     * @return bool
     */
    public function compareCurrency(): bool
    {
        if ($this->cardRepository->getCurrencyFrom(Arr::get($this->tranInfo, 'numberFrom', null)) ==
            $this->cardRepository->getCurrencyTo(Arr::get($this->tranInfo, 'numberTo', null))) {
            return true;
        }
        return false;
    }

    /**
     * Checking correct format of phone number
     * @return bool
     */
    public function checkPhoneNumber(): bool
    {
        return preg_match('/^(((\+380)[0-9]{9})?|([A-Za-z]{0,}))$/', $this->phoneNumber);
    }

    /**
     * Return result of creating transaction
     * @return bool
     */
    public function createTransfer(): bool
    {
        return (bool) $this->transferRepository->create([
            'card_from' => $this->cardRepository
                ->find(Arr::get($this->tranInfo, 'numberFrom', null))['number'],
            'card_to' => Arr::get($this->tranInfo, 'numberTo', null),
            'date' => date('Y-m-d H:i:s'),
            'sum' => Arr::get($this->tranInfo, 'sum', null),
            'new_sum' => $this->getBalanceFrom() - Arr::get($this->tranInfo, 'sum', null),
            'currency' => $this->cardRepository
                ->getCurrencyFrom(Arr::get($this->tranInfo, 'numberFrom', null)),
            'comment' => Arr::get($this->tranInfo, 'comment', null)
        ]) ?? false;
    }

    /**
     * Updating card information
     */
    public function updateCards()
    {
        $this->cardRepository->updateFrom(Arr::get($this->tranInfo, 'numberFrom', null),
                    ['sum' => $this->getBalanceFrom() - Arr::get($this->tranInfo, 'sum', null)]);
        $this->cardRepository->updateTo(Arr::get($this->tranInfo, 'numberTo', null),
                    ['sum' => $this->getBalanceTo() + Arr::get($this->tranInfo, 'sum', null)]);
    }


    /**
     * Return collection with info about all card transfers
     * @param int $cardId
     * @return Collection
     */
    public function getCardTransfers($cardId): Collection
    {
        $card = $this->cardRepository->find($cardId);
        return $this->transferRepository->getCardTransactions(Arr::get($card, 'number', null));
    }

}
