<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Loan;
use App\Repository\ActiveLoanRepositoryInterface;
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
     * @return bool
     */
    public function decrease(): bool
    {
        $loans = $this->getLoansByDate();

        foreach ($loans as $loan) {
            $loanId = Arr::get($loan, 'id', null);
            $monthLeft = Arr::get($loan, 'month_left', null);

            $time1 = microtime(TRUE);

            $monthSum = Arr::get($loan, 'month_pay', null);
            $cardFrom = $this->cardRepository->find(Arr::get($loan, 'card_id', null));
            $bankCurrency = Arr::get($cardFrom, 'currency', null);
            $bankSum = $this->cardRepository->generalSumByCurrency($bankCurrency);

            DB::transaction(function () use ($cardFrom, $bankSum, $bankCurrency, $monthSum, $loan, $monthLeft, $loanId) {
                $this->model->where('id', $loanId)->update(['month_left' => $monthLeft-1]);
                $this->cardRepository->updateSum(Arr::get($loan, 'card_id', null), $monthSum);
                $this->cardRepository->updateGeneral($bankCurrency, ['sum' => $bankSum + $monthSum]);
                $this->transferRepository->create([
                    'card_from' => Arr::get($cardFrom, 'id', null),
                    'card_to' => 'Bank',
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => $monthSum,
                    'new_sum' => $monthSum,
                    'currency' => Arr::get($cardFrom, 'currency', null),
                    'comment' => 'Loan decrease',
                    'user_id' => Arr::get($loan, 'user_id', null)
                ]);
            });

            $time2 = microtime(TRUE);
            $time = $time2 - $time1;
            file_put_contents('debug.txt', "\n\n Updating time: " . $time, FILE_APPEND);

            if ($monthLeft <= 0) {
                $time1 = microtime(TRUE);

                $this->delete($loanId);

                $time2 = microtime(TRUE);
                $time = $time2 - $time1;
                file_put_contents('debug.txt', "\n\n Deleting time: " . $time, FILE_APPEND);
            }
        }
        return true;
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
