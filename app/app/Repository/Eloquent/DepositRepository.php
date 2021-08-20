<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveDeposit;
use App\Models\Deposit;
use App\Repository\DepositRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DepositRepository extends BaseRepository implements DepositRepositoryInterface
{
    /**
     * DepositRepository constructor.
     *
     * @param Deposit $model
     */
    public function __construct(Deposit $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get existing loan by id
     * @return Model|null
     */
    public function getDeposit($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @param array $deposit
     * @param int $user_id
     * @return bool
     */
    public function newDeposit($id, $deposit, $user_id): bool
    {
        $baseDeposit = $this->getDeposit($id);
        return (bool) ActiveDeposit::create([
            'deposit_id' => $id,
            'sum' => Arr::get($deposit, 'sum', 0),
            'total_sum' => Arr::get($deposit, 'sum', 0),
            'currency' => Arr::get($deposit, 'currency', null),
            'month_pay' => round(($deposit['percent'] * 0.01 * $deposit['sum']) / $deposit['duration'], 2),
            'duration' => Arr::get($deposit, 'duration', 0),
            'month_left' => Arr::get($deposit, 'duration', 0),
            'early_percent' => $baseDeposit['early_percent'] == $deposit['percent'] ? $deposit['percent'] : 0,
            'intime_percent' => $baseDeposit['intime_percent'] == $deposit['percent'] ? $deposit['percent'] : 0,
            'card_id' => Arr::get($deposit, 'numberFrom', null),
            'user_id' => $user_id
        ]) ?? false;
    }


}
