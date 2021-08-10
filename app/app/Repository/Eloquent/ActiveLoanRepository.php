<?php

namespace App\Repository\Eloquent;

use App\Models\ActiveLoan;
use App\Models\Loan;
use App\Repository\ActiveLoanRepositoryInterface;
use Illuminate\Support\Collection;

class ActiveLoanRepository extends BaseRepository implements ActiveLoanRepositoryInterface
{
    /**
     * LoanRepository constructor.
     *
     * @param ActiveLoan $model
     */
    public function __construct(ActiveLoan $model)
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

    public function decrease($id): bool
    {
        return (bool) $this->model->update()
    }

}
