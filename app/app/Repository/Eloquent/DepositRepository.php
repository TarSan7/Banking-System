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

    private $activeDeposit;
    /**
     * DepositRepository constructor.
     *
     * @param Deposit $model
     */
    public function __construct(Deposit $model, ActiveDeposit $activeDeposit)
    {
        parent::__construct($model);
        $this->activeDeposit = $activeDeposit;
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
        $percent = Arr::get($deposit, 'percent', 0);
        $duration = Arr::get($deposit, 'duration', null);
        $sum = Arr::get($deposit, 'sum', 0);
        $monthPay = round(($percent * 0.01 * $sum) / $duration, 2);
        $earlyPercent = Arr::get($baseDeposit, 'early_percent', null) == $percent;
        $intimePercent = Arr::get($baseDeposit, 'intime_percent', null) == $percent;

        return (bool) $this->activeDeposit->create([
            'deposit_id' => $id,
            'sum' => $sum,
            'total_sum' => $sum,
            'currency' => Arr::get($deposit, 'currency', null),
            'month_pay' => $monthPay,
            'duration' => $duration,
            'month_left' => $duration,
            'early_percent' => $earlyPercent ? $percent : 0,
            'intime_percent' => $intimePercent ? $percent : 0,
            'card_id' => Arr::get($deposit, 'numberFrom', null),
            'user_id' => $user_id
        ]) ?? false;
    }


}
