<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveDeposit;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Repository\ActiveDepositRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ActiveDepositRepository extends BaseRepository implements ActiveDepositRepositoryInterface
{
    private $cardRepository, $transferRepository;
    /**
     * DepositRepository constructor.
     *
     * @param ActiveDeposit $model
     */
    public function __construct(
        ActiveDeposit $model,
        CardRepository $cardRepository,
        TransferRepository $transferRepository
    ) {
        parent::__construct($model);
        $this->cardRepository = $cardRepository;
        $this->transferRepository = $transferRepository;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @return Collection
     */
    public function getCardsId(): Collection
    {
        return $this->model->get('card_id');
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function userDeposits($userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * @param int $deposit_id
     */
    public function getMoney($deposit_id): void
    {
        $deposit = $this->find($deposit_id);
        $currency = Arr::get($deposit, 'currency', null);
        $bankSum = $this->cardRepository->generalSumByCurrency($currency);
        $this->cardRepository->updateGeneral(
            $currency,
            ['sum' => $bankSum - Arr::get($deposit, 'total_sum', 0)]
        );
        $this->cardRepository->updateSum(
            Arr::get($deposit, 'card_id', null),
            -Arr::get($deposit, 'total_sum', 0)
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        return (bool) $this->model->where('id', $id)->delete();
    }

    /**
     * @param array $deposits
     * @return bool
     */
    public function decrease($deposits): bool
    {
        foreach ($deposits as $deposit) {
            $depositId = Arr::get($deposit, 'id', 0);
            $monthLeft = Arr::get($this->model->where('id', $depositId)->first(), 'month_left', null);
            $createDate = date('d', strtotime(Arr::get($deposit, 'created_at', null)));
            if ($monthLeft <= 0) {
                $this->getMoney($depositId);
                $this->delete($depositId);
            } elseif ($createDate === date('d')) {
                $change = $monthLeft - 1;
                $monthSum = Arr::get($this->model->where('id', $depositId)->first(), 'month_pay', null);
                $this->model->where('id', $depositId)->update([
                    'month_left' => $change,
                    'total_sum' => Arr::get($deposit, 'total_sum', null) + $monthSum
                ]);
                $this->transferRepository->create([
                    'card_from' => 'Bank',
                    'card_to' => 'Deposit',
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => $monthSum,
                    'new_sum' => $monthSum,
                    'currency' => Arr::get($deposit, 'currency', null),
                    'comment' => 'Percents to deposit',
                    'user_id' => Arr::get($deposit, 'user_id', null)
                ]);
            }
        }
        return true;
    }

}
