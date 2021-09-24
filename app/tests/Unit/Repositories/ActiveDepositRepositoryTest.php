<?php

namespace Tests\Unit\Repositories;

use App\Models\ActiveDeposit;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Deposit;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\DepositRepository;
use App\Repository\Eloquent\TransferRepository;
use Database\Seeders\ActiveDepositSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ActiveDepositRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ActiveDepositRepository
     */
    private $activeDepositRepository, $depositRepository;
    private $newDeposits = [
        array(
            'sum' => 199,
            'currency' => 'UAH',
            'percent' => 20,
            'duration' => 4,
            'numberFrom' => 2
        ),
        array(
            'sum' => 199,
            'currency' => 'UAH',
            'percent' => 20,
            'duration' => 0,
            'numberFrom' => 2
        )
    ];

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->seed(ActiveDepositSeeder::class);

        $this->activeDepositRepository = new ActiveDepositRepository(
            new ActiveDeposit(),
            new CardRepository(new Card()),
            new TransferRepository(new CardTransfer())
        );
        $this->depositRepository = new DepositRepository(new Deposit(), new ActiveDeposit());
    }

    /**
     * Getting all fields of ActiveDeposit
     */
    public function testAll(): void
    {
        $deposit = $this->activeDepositRepository->all();
        $this->assertCount(1, $deposit);
        $depositFirst = Arr::get($deposit, 0, null);
        $this->assertLessThan(25, Arr::get($depositFirst, 'duration', null));
    }

    /**
     * Getting cardsId from all active deposits
     */
    public function testGetCardsId(): void
    {
        $cards = $this->activeDepositRepository->getCardsId();
        $this->assertCount(1, $cards);
    }

    /**
     * Decrease deposit sum
     */
    public function testDecrease(): void
    {
        $this->assertTrue($this->activeDepositRepository->decrease());
    }

    /**
     * Deleting active loan
     */
    public function testDelete(): void
    {
        $this->assertTrue($this->activeDepositRepository->delete());
    }

    /**
     * Checking number of user deposits
     */
    public function testUserDeposits(): void
    {
        $this->assertCount(1, $this->activeDepositRepository->userDeposits(1));
    }
}
