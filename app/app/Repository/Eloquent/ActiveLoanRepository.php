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

    /**
     * @param array $loans
     * @return bool
     */
    public function decrease($loans = null): bool
    {
        if (!$loans) {
            $loans = $this->model->all();
        }
        foreach ($loans as $loan) {
            $loanId = Arr::get($loan, 'id', null);
            $monthLeft = Arr::get($this->model->where('id', $loanId)->first(), 'month_left', null);
            $createDate = date('d', strtotime(Arr::get($loan, 'created_at', null)));
            if ($monthLeft <= 0) {
                $this->delete($loanId);
            } elseif ($createDate === date('d')) {
                $change = $monthLeft - 1;
                $monthSum = Arr::get($this->model->where('id', $loanId)->first(), 'month_pay', null);
                $this->model->where('id', $loanId)->update(['month_left' => $change]);
                $this->cardRepository->updateSum(Arr::get($loan, 'card_id', null), $monthSum);

                $baseLoanId = Arr::get($this->model->find($loanId), 'loan_id', null);
                $bankCurrency = Arr::get(Loan::find($baseLoanId), 'currency', null);
                $bankSum = $this->cardRepository->generalSumByCurrency($bankCurrency);
                $this->cardRepository->updateGeneral($bankCurrency, ['sum' => $bankSum + $monthSum]);

                $this->transferRepository->create([
                    'card_from' => $this->cardRepository->getNumber(Arr::get($loan, 'card_id', null)),
                    'card_to' => 'Bank',
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => $monthSum,
                    'new_sum' => $monthSum,
                    'currency' => $this->cardRepository->getCurrencyFrom(Arr::get($loan, 'card_id', null)),
                    'comment' => 'Loan decrease',
                    'user_id' => Arr::get($loan, 'user_id', null)
                ]);
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
}
