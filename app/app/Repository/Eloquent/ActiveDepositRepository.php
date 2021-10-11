<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveDeposit;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Repository\ActiveDepositRepositoryInterface;
use App\Services\AllTransactionsService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ActiveDepositRepository extends BaseRepository implements ActiveDepositRepositoryInterface
{
    /**
     * @var CardRepository
     */
    private $cardRepository;

    /**
     * DepositRepository constructor
     * @param ActiveDeposit $model
     * @param CardRepository $cardRepository
     */
    public function __construct(
        ActiveDeposit $model,
        CardRepository $cardRepository
    ) {
        parent::__construct($model);
        $this->cardRepository = $cardRepository;
    }

    /**
     * Take all deposits
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Getting cards id
     * @return Collection
     */
    public function getCardsId(): Collection
    {
        return $this->model->get('card_id');
    }

    /**
     * Getting user deposits
     * @param int $userId
     * @return Collection
     */
    public function userDeposits($userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Getting money when closing deposit
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
     * Deleting deposit
     * @param int $id
     * @return bool
     */
    public function delete($id = null): bool
    {
        if ($id === null) {
            $this->model->delete();
        } else {
            $this->model->where('id', $id)->delete();
        }
        return true;
    }

    /**
     * Getting deposits by current date
     * @return object
     */
    public function getDepositsByDate(): object  //think about it
    {
        $date = date('d');
        return $this->model->where('date', 'like', "%-$date")->get();
    }

    /**
     * Getting number of month left
     * @param int $depositId
     * @return array|\ArrayAccess|mixed
     */
    public function getMonthsLeft($depositId)
    {
        return Arr::get($this->model->where('id', $depositId)->first(), 'month_left', 1);
    }

    /**
     * Getting month payment
     * @param int $depositId
     * @return array|\ArrayAccess|mixed
     */
    public function getMonthSum($depositId)
    {
        return Arr::get($this->model->where('id', $depositId)->first(), 'month_pay', null);
    }

    /**
     * Getting id's of deposits
     * @return object
     */
    public function getIds(): object
    {
        return $this->model->select('id')->get();
    }

    /**
     * Update dates for active deposits
     * @param int $id
     * @param string $newDate
     */
    public function updateDate($id, $newDate): void
    {
        $this->model->where('id', $id)->update(['created_at' => $newDate]);
    }

}
