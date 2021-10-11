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
     * @var ActiveLoan
     */
    private $activeLoan;

    /**
     * LoanRepository constructor.
     * @param Loan $model
     * @param ActiveLoan $activeLoan
     */
    public function __construct(Loan $model, ActiveLoan $activeLoan)
    {
        parent::__construct($model);
        $this->activeLoan = $activeLoan;
    }

    /**
     * Getting all loans
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Getting loan by id
     * Get existing loan by id
     * @return Model|null
     */
    public function getLoan($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Getting currency bi loan id
     * @param int $id
     * @return String
     */
    public function getCurrency($id): String
    {
        return $this->model->find($id)->first()->currency;
    }

    /**
     * Creating a new loan
     * @param int $id
     * @param float $sum
     * @param int $card_id
     * @return bool
     */
    public function newLoan($id, $sum, $card_id, $user_id): bool
    {
        $loan = $this->getLoan($id);
        $percent = Arr::get($loan, 'percent', 0);
        $duration = Arr::get($loan, 'duration', null);
        $sumPercents = $sum * ($percent * $duration) / 12 * 0.01;
        if (in_array((int) date('d'), [29, 30, 31])) {
            $date = date('Y-m-d', strtotime('first day of next month'));
        } else {
            $date = date('Y-m-d');
        }
        $total = $sum + $sumPercents;
        return (bool) $this->activeLoan->create([
            'loan_id' => $id,
            'sum' => $sum,
            'total_sum' => $total,
            'month_pay' => $total / $duration,
            'month_left' => $duration,
            'card_id' => $card_id,
            'user_id' => $user_id,
            'date' => $date
        ]) ?? false;
    }
}
