<?php

namespace App\Repository\Eloquent;

use App\Models\CardTransfer;
use App\Repository\TransferRepositoryInterface;
use Illuminate\Support\Collection;

class TransferRepository extends BaseRepository implements TransferRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param CardTransfer $model
     */
    public function __construct(CardTransfer $model)
    {
        parent::__construct($model);
    }

    /**
     * Getting all transfers
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Getting transactions of card
     * @param integer $number
     * @return ?Collection
     */
    public function getCardTransactions($number): ?Collection
    {
       return $this->model->select('*')->where('card_from', $number)
           ->orWhere('card_to', $number)->orderByDesc('date')->get() ?? null;
    }

    /**
     * Getting all user cards transactions
     * @param string $number
     * @return Collection
     */
    public function allTransactions($numbers): Collection
    {
        return $this->model->select('*')->whereIn('card_from', $numbers)
            ->orWhereIn('card_to', $numbers)->orderByDesc('date')->get();
    }

}
