<?php

namespace Tests\Unit\Repositories;

use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Loan;
use App\Repository\Eloquent\ActiveLoanRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\LoanRepository;
use App\Repository\Eloquent\TransferRepository;
use Illuminate\Support\Arr;
use Tests\TestCase;

class LoanRepositoryTest extends TestCase
{
    /**
     * @var LoanRepository
     */
    private $loanRepository, $activeLoanRepository;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->loanRepository = new LoanRepository(
            new Loan(),
            new CardRepository(new Card()),
            new ActiveLoan()
        );
        $this->activeLoanRepository = new ActiveLoanRepository(
            new ActiveLoan(),
            new CardRepository(new Card()),
            new TransferRepository(new CardTransfer())
        );
    }

    /**
     * Getting all fields of Loan
     */
    public function testAll(): void
    {
        $this->assertEquals(Loan::all(), $this->loanRepository->all());
    }

    /**
     * Get existing loan by id
     */
    public function testGetLoan(): void
    {
        $model = $this->loanRepository->getLoan(1);
        $this->assertEquals('Best', Arr::get($model, 'title', null));
    }

    /**
     * Getting currency by loans id
     */
    public function testGetCurrency(): void
    {
        $this->assertEquals('EUR', $this->loanRepository->getCurrency(1));
    }

    /**
     * Creating new loan
     */
    public function testNewLoan(): void
    {
        if ($this->loanRepository->newLoan(1, 250, 2, 0)) {
            $this->assertTrue(ActiveLoan::where('loan_id', 1)->where('sum', 250)
                ->where('card_id', 2)->where('user_id', 0)->exists());
            $loan = ActiveLoan::where('loan_id', 1)->where('sum', 250)->where('user_id', 0)->first();
            CardTransfer::where('user_id', 0)->delete();
            $this->activeLoanRepository->delete(Arr::get($loan, 'id', null));
        }
    }
}
