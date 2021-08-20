<?php

namespace Tests\Unit\Repositories;

use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\Loan;
use App\Repository\Eloquent\ActiveLoanRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\LoanRepository;
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
        $this->loanRepository = new LoanRepository(new Loan());
        $this->activeLoanRepository = new ActiveLoanRepository(new ActiveLoan(),
            new CardRepository(new Card()));
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
        $this->assertEquals('Best', $model['title']);
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
        if ($this->loanRepository->newLoan(1, 250, 2, 2)) {
            $this->assertTrue(ActiveLoan::where('loan_id', 1)->where('sum', 250)
                ->where('card_id', 2)->where('user_id', 2)->exists());
            $loan = ActiveLoan::where('loan_id', 1)->where('sum', 250)->where('card_id', 2)->get()[0];
            $this->activeLoanRepository->delete($loan['id']);
        }
    }
}