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
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ActiveLoanRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ActiveLoanRepository
     */
    private $activeLoanRepository, $loanRepository;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(UserSeeder::class);

        $this->activeLoanRepository = new ActiveLoanRepository(
            new ActiveLoan(),
            new CardRepository(new Card()),
            new TransferRepository(new CardTransfer())
        );
        $this->loanRepository = new LoanRepository(
            new Loan(),
            new CardRepository(new Card()),
            new ActiveLoan()
        );
    }

    /**
     * Getting all fields of ActiveLoan
     */
    public function testAll(): void
    {
        $this->assertCount(0, $this->activeLoanRepository->all());
    }

    /**
     * Getting cardsId from all active loans
     */
    public function testGetCardsId(): void
    {
        $cards = $this->activeLoanRepository->getCardsId();
        $this->assertCount(0, $cards);
    }

    /**
     * Decrease loan sum
     */
    public function testDecrease(): void
    {
        $this->loanRepository->newLoan(1, 250, 2, 1);
        for ($i = 0; $i < 6; $i++) {
            $this->assertTrue($this->activeLoanRepository->decrease());
        }
    }

    /**
     * Deleting active loan
     */
    public function testDelete(): void
    {
        $this->loanRepository->newLoan(1, 250, 2, 1);
        $this->assertTrue($this->activeLoanRepository->delete());
    }

    /**
     * Checking user loans
     */
    public function testUserLoans(): void
    {
        $this->assertCount(0, $this->activeLoanRepository->userLoans(1));
    }

}
