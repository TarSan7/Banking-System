<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveLoan;
use App\Repository\ActiveLoanRepositoryInterface;
use Illuminate\Support\Collection;

class ActiveLoanRepository extends BaseRepository implements ActiveLoanRepositoryInterface
{
    private $cardRepository;
    /**
     * LoanRepository constructor.
     *
     * @param ActiveLoan $model
     */
    public function __construct(ActiveLoan $model, CardRepository $cardRepository)
    {
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
     * @param array $cardId
     * @return bool
     */
    public function decrease($cardId): bool
    {
        foreach ($cardId as $id) {
            $monthLeft = $this->model->where('card_id', $id['card_id'])->get('month_left')[0]['month_left'];
            if ($monthLeft <= 0) {
                $this->delete($id['card_id']);
            }
            $change = $monthLeft - 1;
            $monthSum = $this->model->where('card_id', $id['card_id'])->get('month_pay')[0]['month_pay'];
            if (!$this->model->where('card_id', $id['card_id'])->update(['month_left' => $change])
                || !$this->cardRepository->updateSum($id['card_id'], $monthSum)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $cardId
     * @return bool
     */
    public function delete($cardId): bool
    {
        return (bool) $this->model->where('card_id', $cardId)->delete();
    }

}
