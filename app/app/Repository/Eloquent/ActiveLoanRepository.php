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
    public function decrease($loans): bool
    {
        foreach ($loans as $loan) {
            $loanId = $loan['id'];
            $monthLeft = $this->model->where('id', $loanId)
                ->get('month_left')[0]['month_left'];
            if ($monthLeft <= 0) {
                $this->delete($loanId);
            } elseif (date('d', strtotime($loan['created_at'])) == date('d')) {
                $change = $monthLeft - 1;
                $monthSum = $this->model->where('id', $loanId)->get('month_pay')[0]['month_pay'];
                $this->model->where('id', $loanId)->update(['month_left' => $change]);
                $this->cardRepository->updateSum($loan['card_id'], $monthSum);

                $bankCurrency = Loan::find(ActiveLoan::find($loanId)['loan_id'])['currency'];
                $bankSum = Card::where('type', 'general')->where('currency', $bankCurrency)->get('sum')[0]['sum'];
                Card::where('type', 'general')->where('currency', $bankCurrency)->update(['sum' => $bankSum + $monthSum]);

                CardTransfer::create([
                    'card_from' => $this->cardRepository->getNumber($loan['card_id']),
                    'card_to' => 'Bank',
                    'date' => date('Y-m-d H:i:s'),
                    'sum' => $monthSum,
                    'new_sum' => $monthSum,
                    'currency' => Card::where('id', Arr::get($loan, 'card_id', null), $monthSum)
                        ->get('currency')[0]['currency'],
                    'comment' => "Loan decrease",
                    'user_id' => $loan['user_id']
                ]);
            }
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete($id): bool
    {
        $this->model->where('id', $id)->delete();
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
