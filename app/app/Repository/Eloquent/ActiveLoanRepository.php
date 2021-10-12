<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Loan;
use App\Repository\ActiveLoanRepositoryInterface;
use App\Services\AllTransactionsService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActiveLoanRepository extends BaseRepository implements ActiveLoanRepositoryInterface
{
    /**
     * LoanRepository constructor.
     *
     * @param ActiveLoan $model
     */
    public function __construct(
        ActiveLoan $model
    ) {
        parent::__construct($model);
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
     * Getting cards id's
     * @return Collection
     */
    public function getCardsId(): Collection
    {
        return $this->model->get('card_id');
    }

    /**
     * Getting loans by current dates
     * @return array
     */
    public function getLoansByDate(): array
    {
        $currentDate = date('d');
        if (date('d') === date('d', strtotime("last day of this month"))) {
            return DB::select('select * from active_loans where dayofmonth(date) > :current_date',
                ['current_date' => $currentDate - 1]);
        } else {
            return DB::select('select * from active_loans where dayofmonth(date) = :current_date',
                ['current_date' => $currentDate]);
        }

    }

    /**
     * Deleting loans
     * @param int $id
     * @return bool
     */
    public function delete($id = null): bool
    {
        if (!$id) {
            $this->model->delete();
        } else {
            $this->model->where('id', $id)->delete();
        }
        return true;
    }

    /**
     * Getting user loans
     * @param int $userId
     * @return Collection
     */
    public function userLoans($userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Getting id's
     * @return object
     */
    public function getIds(): object
    {
        return $this->model->select('id')->get();
    }

    /**
     * Updating dates of loans
     * @param $id
     * @param $newDate
     * @return bool
     */
    public function updateDate($id, $newDate): bool
    {
        return $this->model->where('id', $id)->update(['created_at' => $newDate]);
    }

}
