<?php
//
//namespace App\Services;
//
//use App\Repository\Eloquent\CardRepository;
//use App\Repository\Eloquent\TransferRepository;
//use App\Repository\Eloquent\UserRepository;
//use Illuminate\Support\Arr;
//use Illuminate\Support\Collection;
//use Illuminate\Support\Facades\Auth;
//
//class TransferService
//{
//    /**
//     * @var CardRepository
//     * @var TransferRepository
//     * @var UserRepository
//     * @var array
//     * @var string
//     */
//    private $cardRepository, $transferRepository, $userRepository, $allTransactionService, $tranInfo, $phoneNumber;
//
//    const PHONE = 'phone', INTERNET = 'internet';
//    /**
//     * Responses for controller
//     */
//    const RESPONSES = array(
//        'sum' => 'Not enough resource!',
//        'currency' => 'Different currencies! Try another card.',
//        'cards' => 'Same cards! Try another card.',
//        'form' => 'An error occurred while transfer.',
//        'phone' => 'Incorrect number format!',
//        'done' => 'Done!'
//    );
//
//    /**
//     * @param CardRepository $cardRepository
//     * @param TransferRepository $transferRepository
//     * @param UserRepository $userRepository
//     */
//    public function __construct(
//        CardRepository $cardRepository,
//        TransferRepository $transferRepository,
//        UserRepository $userRepository,
//        AllTransactionsService $allTransactionService
//    ) {
//        $this->cardRepository = $cardRepository;
//        $this->transferRepository = $transferRepository;
//        $this->userRepository = $userRepository;
//        $this->allTransactionService = $allTransactionService;
//    }
//
//    /**
//     * Saving information about transfer
//     * @param array $info
//     * @param string|null $id
//     */
//    public function setInfo($info, $id = null)
//    {
//        $this->tranInfo = $info;
//        if ($id) {
//            $this->phoneNumber = Arr::get($this->tranInfo, 'numberTo', null);
//            $this->tranInfo['comment'] .= 'To: ' . Arr::get($this->tranInfo, 'numberTo', null);
//            $this->tranInfo['numberTo'] = $this->cardRepository
//                ->getGeneralCardNum(Arr::get($this->tranInfo, 'numberFrom', null));
//        }
//    }
//
//    /**
//     * Return balance of card from which make transaction
//     * @return float
//     */
//    public function getBalanceFrom(): float
//    {
//        return $this->cardRepository->getSumFrom(Arr::get($this->tranInfo, 'numberFrom', null));
//    }
//
//    /**
//     * Return balance of card to which make transaction
//     * @return float
//     */
//    public function getBalanceTo(): float
//    {
//        return $this->cardRepository->getSumTo(Arr::get($this->tranInfo, 'numberTo', null));
//    }
//
//    /**
//     * Return sum of transfer
//     * @return float
//     */
//    public function transferSum(): float
//    {
//        return Arr::get($this->tranInfo, 'sum', null);
//    }
//
//    /**
//     * Comparing currency of cards
//     * @return bool
//     */
//    public function compareCurrency(): bool
//    {
//        if ($this->cardRepository->getCurrencyFrom(Arr::get($this->tranInfo, 'numberFrom', null)) ===
//            $this->cardRepository->getCurrencyTo(Arr::get($this->tranInfo, 'numberTo', null))) {
//            return true;
//        }
//        return false;
//    }
//
//    /**
//     * Comparing numbers of cards
//     * @return bool
//     */
//    public function compareNumbers(): bool
//    {
//        if ($this->cardRepository->getId(Arr::get($this->tranInfo, 'numberTo', null)) ===
//            Arr::get($this->tranInfo, 'numberFrom', null)) {
//            return true;
//        }
//        return false;
//    }
//
//    /**
//     * Checking correct format of phone number
//     * @return bool
//     */
//    public function checkPhoneNumber(): bool
//    {
//        return preg_match('/^(((\+380)[0-9]{9})?|([A-Za-z]{0,}))$/', $this->phoneNumber);
//    }
//
//    /**
//     * Updating card information
//     */
//    public function updateCards()
//    {
//        $this->cardRepository->updateFrom(Arr::get($this->tranInfo, 'numberFrom', null),
//                    ['sum' => $this->getBalanceFrom() - Arr::get($this->tranInfo, 'sum', null)]);
//        $this->cardRepository->updateTo(Arr::get($this->tranInfo, 'numberTo', null),
//                    ['sum' => $this->getBalanceTo() + Arr::get($this->tranInfo, 'sum', null)]);
//    }
//
//    /**
//     * Return collection with info about all card transfers
//     * @param int $cardId
//     * @return Collection
//     */
//    public function getCardTransfers($cardId): Collection
//    {
//        $card = $this->cardRepository->find($cardId);
//        return $this->transferRepository->getCardTransactions(Arr::get($card, 'number', null));
//    }
//
//    /**
//     * Return info about card
//     * @return array
//     */
//    public function numberFromInfo(): array
//    {
//        return array(
//            'number' => Arr::get($this->tranInfo, 'numberFrom', null),
//            'sum' => $this->getBalanceFrom() - Arr::get($this->tranInfo, 'sum', null)
//        );
//    }
//
//    /**
//     * Return info about card
//     * @return array
//     */
//    public function numberToInfo(): array
//    {
//        return array(
//            'number' => Arr::get($this->tranInfo, 'numberTo', null),
//            'sum' => $this->getBalanceTo() + Arr::get($this->tranInfo, 'sum', null)
//        );
//    }
//
//    /**
//     * @return array
//     */
//    public function cardCheck(): array
//    {
//        if ($this->transferSum() > $this->getBalanceFrom()) {
//            return ['error', Arr::get(self::RESPONSES, 'sum', null)];
//        } elseif (!$this->compareCurrency()) {
//            return ['error', Arr::get(self::RESPONSES, 'currency', null)];
//        } elseif ($this->compareNumbers()) {
//            return ['error', Arr::get(self::RESPONSES, 'cards', null)];
//        } else {
//            $this->allTransactionService->make(
//                $this->createTransfer(),
//                $this->numberFromInfo(),
//                $this->numberToInfo()
//            );
//            return ['success', Arr::get(self::RESPONSES, 'done', null)];
//        }
//    }
//
//    /**
//     * @param string $id
//     * @return array
//     */
//    public function otherCheck($id): array
//    {
//        if ($id === self::PHONE && !$this->checkPhoneNumber()) {
//            return ['error', Arr::get(self::RESPONSES, 'phone', null)];
//        } elseif ($this->transferSum() > $this->getBalanceFrom()) {
//            return ['error', Arr::get(self::RESPONSES, 'sum', null)];
//        } else {
//            $this->allTransactionService->make(
//                $this->createTransfer(),
//                $this->numberFromInfo(),
//                $this->numberToInfo()
//            );
//            return ['success', Arr::get(self::RESPONSES, 'done', null)];
//        }
//    }
//
//    /**
//     * @return array
//     */
//    public function getTransactions(): array
//    {
//        $transactions = array();
//        $all = array();
//        foreach ($this->userRepository->getCards(Auth::id()) as $one) {
//            $all[] = $this->cardRepository->getNumber(Arr::get($one, 'card_id', null));
//        }
//        foreach ($all as $card) {
//            $oneCard = $this->transferRepository->getCardTransactions($card);
//            if (Arr::get($oneCard, 0, null)) {
//                $transactions[] = array(
//                    'number' => $card,
//                    'oneCard' => $oneCard
//                );
//            }
//        }
//        return $transactions;
//    }
//}


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
     * @var UserRepository
     * @var array
     * @var string
     */
    private $cardRepository, $transferRepository, $userRepository, $allTransactionsService, $tranInfo, $phoneNumber;

    const PHONE = 'phone', INTERNET = 'internet';
    /**
     * Responses for controller
     */
    const RESPONSES = array(
        'sum' => 'Not enough resource!',
        'currency' => 'Different currencies! Try another card.',
        'cards' => 'Same cards! Try another card.',
        'form' => 'An error occurred while transfer.',
        'phone' => 'Incorrect number format!',
        'done' => 'Done!'
    );

    /**
     * @param CardRepository $cardRepository
     * @param TransferRepository $transferRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        CardRepository     $cardRepository,
        TransferRepository $transferRepository,
        UserRepository     $userRepository,
        AllTransactionsService $allTransactionsService
    )
    {
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
        $this->userRepository = $userRepository;
        $this->allTransactionsService = $allTransactionsService;
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
            $this->tranInfo['comment'] .= 'To: ' . Arr::get($this->tranInfo, 'numberTo', null);
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
        if ($this->cardRepository->getCurrencyFrom(Arr::get($this->tranInfo, 'numberFrom', null)) ===
            $this->cardRepository->getCurrencyTo(Arr::get($this->tranInfo, 'numberTo', null))) {
            return true;
        }
        return false;
    }

    /**
     * Comparing numbers of cards
     * @return bool
     */
    public function compareNumbers(): bool
    {
        if ($this->cardRepository->getId(Arr::get($this->tranInfo, 'numberTo', null)) ===
            Arr::get($this->tranInfo, 'numberFrom', null)) {
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
     * @return array
     */
    public function createTransfer(): array
    {
        return array(
            'card_from' => $this->cardRepository
                ->find(Arr::get($this->tranInfo, 'numberFrom', null))['number'],
            'card_to' => Arr::get($this->tranInfo, 'numberTo', null),
            'date' => date('Y-m-d H:i:s'),
            'sum' => Arr::get($this->tranInfo, 'sum', null),
            'new_sum' => $this->getBalanceFrom() - Arr::get($this->tranInfo, 'sum', null),
            'currency' => $this->cardRepository
                ->getCurrencyFrom(Arr::get($this->tranInfo, 'numberFrom', null)),
            'comment' => Arr::get($this->tranInfo, 'comment', null),
            'user_id' => Auth::user()->id ?? 0
        );
    }

    /**
     * @return array
     */
    public function infoFrom(): array
    {
        return array(
            Arr::get($this->tranInfo, 'numberFrom', null),
            ['sum' => $this->getBalanceFrom() - Arr::get($this->tranInfo, 'sum', null)]
        );
    }

    /**
     * @return array
     */
    public function infoTo(): array
    {
        return array(
            Arr::get($this->tranInfo, 'numberTo', null),
            ['sum' => $this->getBalanceTo() + Arr::get($this->tranInfo, 'sum', null)]
        );
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

    /**
     * @return array
     */
    public function cardCheck(): array
    {
        if ($this->transferSum() > $this->getBalanceFrom()) {
            return ['error', Arr::get(self::RESPONSES, 'sum', null)];
        } elseif (!$this->compareCurrency()) {
            return ['error', Arr::get(self::RESPONSES, 'currency', null)];
        } elseif ($this->compareNumbers()) {
            return ['error', Arr::get(self::RESPONSES, 'cards', null)];
        } else {
            $this->allTransactionsService->make($this->createTransfer(), $this->infoFrom(), $this->infoTo());
            return ['success', Arr::get(self::RESPONSES, 'done', null)];
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function otherCheck($id): array
    {
        if ($id === self::PHONE && !$this->checkPhoneNumber()) {
            return ['error', Arr::get(self::RESPONSES, 'phone', null)];
        } elseif ($this->transferSum() > $this->getBalanceFrom()) {
            return ['error', Arr::get(self::RESPONSES, 'sum', null)];
        } else {
            $this->allTransactionsService->make($this->createTransfer(), $this->infoFrom(), $this->infoTo());
            return ['success', Arr::get(self::RESPONSES, 'done', null)];
        }
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        $transactions = array();
        $all = array();
        foreach ($this->userRepository->getCards(Auth::id()) as $one) {
            $all[] = $this->cardRepository->getNumber(Arr::get($one, 'card_id', null));
        }
        foreach ($all as $card) {
            $oneCard = $this->transferRepository->getCardTransactions($card);
            if (Arr::get($oneCard, 0, null)) {
                $transactions[] = array(
                    'number' => $card,
                    'oneCard' => $oneCard
                );
            }
        }
        return $transactions;
    }
}
