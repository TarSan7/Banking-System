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
    private $cardRepository, $transferRepository;
    /**
     * LoanRepository constructor.
     *
     * @param ActiveLoan $model
     */
    public function __construct(
        ActiveLoan $model,
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

    public function getLoansByDate(): object
    {
        $date = date('d');
        return $this->model->where('created_at', 'like', "%-$date %")->get();
    }

    /**
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
     * @param int $userId
     * @return Collection
     */
    public function userLoans($userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
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
