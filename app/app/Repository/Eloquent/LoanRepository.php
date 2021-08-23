<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\Loan;
use App\Repository\LoanRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;

class LoanRepository extends BaseRepository implements LoanRepositoryInterface
{
    /**
     * LoanRepository constructor.
     *
     * @param Loan $model
     */
    public function __construct(Loan $model)
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
    public function getLoan($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @return String
     */
    public function getCurrency($id): String
    {
        return $this->model->find($id)->get('currency')[0]['currency'];
    }

    /**
     * @param int $id
     * @param float $sum
     * @param int $card_id
     * @return bool
     */
    public function newLoan($id, $sum, $card_id, $user_id): bool
    {
        $loan = $this->getLoan($id);
        $cardSum = Card::where('type', 'general')->
                    where('currency', Arr::get($loan, 'currency', null))->get('sum')[0]['sum'];
        $total = $sum + ($sum * (Arr::get($loan, 'percent', 0)
                    * Arr::get($loan, 'duration', 0)) / 12 * 0.01);
        Card::where('type', 'general')->where('currency', Arr::get($loan, 'currency', null))->
                update(['sum' => $cardSum - $sum]);
        return (bool) ActiveLoan::create([
            'loan_id' => $id,
            'sum' => $sum,
            'total_sum' => $total,
            'month_pay' => $total / Arr::get($loan, 'duration', 0),
            'month_left' => Arr::get($loan, 'duration', 0),
            'card_id' => $card_id,
            'user_id' => $user_id
        ]) ?? false;
    }
}
