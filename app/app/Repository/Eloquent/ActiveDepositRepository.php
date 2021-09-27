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
    public function delete($id = null): bool
    {
        if ($id === null) {
            $this->model->delete();
        } else {
            $this->model->where('id', $id)->delete();
        }
        return true;
    }

    public function getDepositsByDate(): object
    {
        $date = date('d');
        return $this->model->where('created_at', 'like', "%-$date %")->get();
    }

    public function getMonthsLeft($depositId)
    {
        return Arr::get($this->model->where('id', $depositId)->first(), 'month_left', 1);
    }

    public function getMonthSum($depositId)
    {
        return Arr::get($this->model->where('id', $depositId)->first(), 'month_pay', null);
    }

    /**
     * Getting dates of creation
     * @return object
     */
    public function getIds(): object
    {
        return $this->model->select('id')->get();
    }

    /**
     * @param $id
     * @param $newDate
     * @return bool
     */
    public function updateDate($id, $newDate): bool
    {
        return $this->model->where('id', $id)->update(['created_at' => $newDate]);
    }

}
