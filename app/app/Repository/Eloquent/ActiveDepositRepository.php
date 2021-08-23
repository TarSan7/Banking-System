<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveDeposit;
use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Repository\ActiveDepositRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ActiveDepositRepository extends BaseRepository implements ActiveDepositRepositoryInterface
{
    private $cardRepository;
    /**
     * DepositRepository constructor.
     *
     * @param ActiveDeposit $model
     */
    public function __construct(
        ActiveDeposit $model,
        CardRepository $cardRepository
    ) {
        parent::__construct($model);
        $this->cardRepository = $cardRepository;
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
        $bankSum = Card::where('type', 'general')->where('currency', $deposit['currency'])->get('sum')[0]['sum'];
        Card::where('type', 'general')->where('currency', $deposit['currency'])->
            update(['sum' => $bankSum - $deposit['total_sum']]);
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
        return (bool)$this->model->where('id', $id)->delete();
    }

    /**
     * @param array $cardId
     * @return bool
     */
    public function decrease($deposits): bool
    {
        foreach ($deposits as $deposit) {
            $depositId = $deposit['id'];
            $monthLeft = $this->model->where('id', $depositId)
                ->get('month_left')[0]['month_left'];
            if ($monthLeft <= 0) {
                $this->getMoney($depositId);
                $this->delete($depositId);
            } elseif (date('d', strtotime($deposit['created_at'])) == date('d')) {
                $change = $monthLeft - 1;
                $monthSum = $this->model->where('id', $depositId)->get('month_pay')[0]['month_pay'];
                $this->model->where('id', $depositId)->update([
                    'month_left' => $change,
                    'total_sum' => $deposit['total_sum'] + $monthSum
                ]);
                CardTransfer::create([
                    'card_from' => 'Bank',
                    'card_to' => 'Deposit',
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => $monthSum,
                    'new_sum' => $monthSum,
                    'currency' => $deposit['currency'],
                    'comment' => "Percents to deposit",
                    'user_id' => $deposit['user_id']
                ]);
            }
        }
        return true;
    }

}
