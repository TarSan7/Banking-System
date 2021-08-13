<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveLoan;
use App\Models\Loan;
use App\Repository\LoanRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
     * @param Integer $id
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
    public function newLoan($id, $sum, $card_id): bool
    {
        $loan = $this->getLoan($id);
        $total = $sum + ($sum * ($loan['percent'] * $loan['duration']) / 12 * 0.01);
        return (bool) ActiveLoan::create([
            'loan_id' => $id,
            'sum' => $sum,
            'total_sum' => $total,
            'month_pay' => $total / $loan['duration'],
            'month_left' => $loan['duration'],
            'card_id' => $card_id
        ]) ?? false;
    }
}
