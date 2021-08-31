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
    private $cardRepository, $activeLoan;
    /**
     * LoanRepository constructor.
     *
     * @param Loan $model
     */
    public function __construct(Loan $model, CardRepository $cardRepository, ActiveLoan $activeLoan)
    {
        parent::__construct($model);
        $this->cardRepository = $cardRepository;
        $this->activeLoan = $activeLoan;
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
        return $this->model->find($id)->first()->currency;
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
        $cardSum = $this->cardRepository->generalSumByCurrency(Arr::get($loan, 'currency', null));
        $percent = Arr::get($loan, 'percent', 0);
        $duration = Arr::get($loan, 'duration', null);
        $sumPercents = $sum * ($percent * $duration) / 12 * 0.01;
        $total = $sum + $sumPercents;
        $this->cardRepository->updateGeneral(Arr::get($loan, 'currency', null), ['sum' => $cardSum - $sum]);
        return (bool) $this->activeLoan->create([
            'loan_id' => $id,
            'sum' => $sum,
            'total_sum' => $total,
            'month_pay' => $total / $duration,
            'month_left' => $duration,
            'card_id' => $card_id,
            'user_id' => $user_id
        ]) ?? false;
    }
}
