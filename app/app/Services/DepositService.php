<?php

namespace App\Services;

use App\Models\ActiveDeposit;
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
    private $depositRepository, $activeDepositRepository, $cardRepository, $transferRepository, $allTransactionsService;

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
        TransferRepository $transferRepository,
        AllTransactionsService $allTransactionsService
    ) {
        $this->depositRepository = $depositRepository;
        $this->activeDepositRepository = $activeDepositRepository;
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
        $this->allTransactionsService = $allTransactionsService;
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
            } else {
                $this->allTransactionsService->takeDeposit(
                    $this->createDepositTransfer($cardNum, $sum, $currency),
                    $deposit,
                    $id,
                    $currency,
                    $sum
                );
                return ['success', Arr::get(self::RESPONSES ,'done', null)];
            }
        } else {
            return ['error', Arr::get(self::RESPONSES, 'tooMuch', null)];
        }
    }

    /**
     * @param string $cardNum
     * @param float $sum
     * @param string $currency
     */
    public function createDepositTransfer($cardNum, $sum, $currency): array
    {
        return array(
            'card_from' => $cardNum,
            'card_to' => 'Bank',
            'date' => date('Y-m-d H:i:s'),
            'sum' => $sum,
            'new_sum' => $sum,
            'currency' => $currency,
            'comment' => 'Take a deposit',
            'user_id' => Auth::user()->id ?? 0
        );
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
        $deposit = $this->activeDepositRepository->find($id);
        $this->allTransactionsService->closeDeposit($id, $deposit);

        return ['success', Arr::get(self::RESPONSES, 'closed', null)];
    }

    /**
     * @return bool
     */
    public function decrease(): bool
    {
        $deposits = $this->activeDepositRepository->getDepositsByDate();

        foreach ($deposits as $deposit) {
            $depositId = Arr::get($deposit, 'id', 1);

            $time1 = microtime(TRUE);

            $monthLeft = $this->activeDepositRepository->getMonthsLeft($depositId);
            $monthSum = $this->activeDepositRepository->getMonthSum($depositId);

            $this->allTransactionsService->depositDecrease($depositId, $monthLeft, $monthSum, $deposit);

            $time2 = microtime(TRUE);
            $time = $time2 - $time1;
            file_put_contents('debugDep.txt', "\n\n Updating time: " . $time, FILE_APPEND);

            if ($monthLeft <= 0) {
                $this->allTransactionsService->closeDeposit($depositId);
            }
        }
        return true;
    }
}
