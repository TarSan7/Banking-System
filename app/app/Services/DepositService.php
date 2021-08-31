<?php

namespace App\Services;

use App\Models\Card;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\DepositRepository;
use App\Repository\Eloquent\TransferRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DepositService
{

    /**
     * @var DepositRepository
     */
    private $depositRepository, $activeDepositRepository, $cardRepository, $transferRepository;

    /**
     * Responses for controller
     */
    const RESPONSES = array(
        'form' => 'An error occurred!',
        'done' => 'Done!',
        'tooMuch' => 'Too much deposits for one User!',
        'money' => 'Not enough money for deposit.',
        'currency' => 'Different currencies.',
        'closed' => 'Deposit closed! Funds were transferred to the card.'
    );

    /**
     * @param DepositRepository $depositRepository
     * @param ActiveDepositRepository $activeDepositRepository
     * @param CardRepository $cardRepository
     * @param TransferRepository $transferRepository
     */
    public function __construct(
        DepositRepository $depositRepository,
        ActiveDepositRepository $activeDepositRepository,
        CardRepository $cardRepository,
        TransferRepository $transferRepository
    ) {
        $this->depositRepository = $depositRepository;
        $this->activeDepositRepository = $activeDepositRepository;
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
    }

    /**
     * Get all existing deposits
     * @return Collection|null
     */
    public function getBaseDeposits(): ?Collection
    {
        return $this->depositRepository->all();
    }

    /**
     * @param $id
     * @return Model|null
     */
    public function oneDeposit($id): ?Model
    {
        return $this->depositRepository->find($id);
    }

    /**
     * @param array $deposit
     * @param integer $id
     * @return bool
     */
    public function newDeposit($deposit, $id): bool
    {
        return $this->depositRepository->newDeposit(
            $id,
            $deposit,
            Auth::id() ?? 0
        ) ?? false;
    }

    /**
     * @param array $deposit
     * @param integer $id
     * @return array
     */
    public function accept($deposit, $id): array
    {
        if ($this->countUserDeposits() < 3) {
            $currency = Arr::get($deposit, 'currency', null);
            $sum = Arr::get($deposit, 'sum', null);
            $cardNum = Arr::get($deposit, 'numberFrom', null);
            $cardSum = $this->cardRepository->getSumTo(Arr::get($deposit, 'numberFrom', null));
            $numberFrom = Arr::get($deposit, 'numberFrom', null);
            $deposit['numberFrom'] = $this->cardRepository->getId($numberFrom);
            if ($cardSum < $sum) {
                return ['error', Arr::get(self::RESPONSES, 'money', null)];
            } elseif ($this->cardRepository->getCurrencyFrom(Arr::get($deposit, 'numberFrom', null)) != $currency) {
                return ['error', Arr::get(self::RESPONSES, 'currency', null)];
            } elseif ($this->newDeposit($deposit, $id)) {
                $this->cardRepository->updateSum(Arr::get($deposit, 'numberFrom', null), $sum);
                $bankSum = $this->cardRepository->generalSumByCurrency($currency);
                $this->cardRepository->updateGeneral($currency, ['sum' => $bankSum + $sum]);
                $this->transferRepository->create([
                    'card_from' => $cardNum,
                    'card_to' => 'Bank',
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => $sum,
                    'new_sum' => $sum,
                    'currency' => $currency,
                    'comment' => 'Take a deposit',
                    'user_id' => Auth::user()->id ?? 0
                ]);
                return ['success', Arr::get(self::RESPONSES ,'done', null)];
            } else {
                return ['error', Arr::get(self::RESPONSES, 'form', null)];
            }
        } else {
            return ['error', Arr::get(self::RESPONSES, 'tooMuch', null)];
        }
    }

    /**
     * @return int
     */
    public function countUserDeposits(): int
    {
        return count($this->activeDepositRepository->userDeposits(Auth::user()->id ?? 0));
    }

    /**
     * @return array
     */
    public function getUserDeposits(): array
    {
        $deposits = $this->activeDepositRepository->userDeposits(Auth::user()->id ?? 0);
        $rez = array();
        foreach ($deposits as $deposit) {
            $base = $this->depositRepository->getDeposit(Arr::get($deposit, 'deposit_id', null));
            $rez[] = array(
                'id' => Arr::get($deposit, 'id', 0),
                'title' => Arr::get($base, 'title', null),
                'early_percent' => Arr::get($deposit,'early_percent', 0),
                'intime_percent' => Arr::get($deposit, 'intime_percent', 0),
                'duration' => Arr::get($deposit, 'duration', null),
                'currency' => Arr::get($deposit, 'currency', null),
                'total-sum' => Arr::get($deposit, 'total_sum', 0),
                'month-pay' => Arr::get($deposit, 'month_pay', 0),
                'month-left' => Arr::get($deposit, 'month_left', 0)
            );
        }
        return $rez;
    }

    /**
     * @param *int $id
     * @return array
     */
    public function close($id): array
    {
        $this->activeDepositRepository->getMoney($id);
        $deposit = $this->activeDepositRepository->find($id);
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
        $this->activeDepositRepository->delete($id);
        return ['success', Arr::get(self::RESPONSES, 'closed', null)];
    }
}
