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
use Illuminate\Support\Arr;
use Tests\TestCase;

class ActiveDepositRepositoryTest extends TestCase
{
    /**
     * @var ActiveDepositRepository
     */
    private $activeDepositRepository, $depositRepository;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
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
        $this->assertEquals(ActiveDeposit::all(), $this->activeDepositRepository->all());
    }

    /**
     * Getting cardsId from all active deposits
     */
    public function testGetCardsId(): void
    {
        $cards = $this->activeDepositRepository->getCardsId();
        $this->assertCount(count(ActiveDeposit::all()), $cards);
    }

    /**
     * Decrease deposit sum
     */
    public function testDecrease(): void
    {
        $this->depositRepository->newDeposit(1, [
            'sum' => 199,
            'currency' => 'UAH',
            'percent' => 20,
            'duration' => 4,
            'numberFrom' => 8
        ], 0);
        $deposit = ActiveDeposit::where('deposit_id', 1)->where('sum', 199)->where('card_id', 8)->first();
        $this->assertTrue($this->activeDepositRepository->decrease([$deposit]));
        ActiveDeposit::where('deposit_id', 1)->where('card_id', 8)->update(['month_left' => 0]);

        $deposit = ActiveDeposit::where('deposit_id', 1)->where('sum', 199)->where('card_id', 8)->first();
        $this->assertTrue($this->activeDepositRepository->decrease([$deposit]));
        $this->activeDepositRepository->delete(Arr::get($deposit, 'id', null));
    }

    /**
     * Deleting active loan
     */
    public function testDelete(): void
    {
        $this->depositRepository->newDeposit(1, [
            'sum' => 199,
            'currency' => 'UAH',
            'percent' => 20,
            'duration' => 4,
            'numberFrom' => 8
        ], 0);
        $deposit = ActiveDeposit::where('deposit_id', 1)->where('sum', 199)->where('card_id', 8)->first();
        $this->assertTrue($this->activeDepositRepository->delete(Arr::get($deposit, 'id', null)));
        CardTransfer::where('user_id', 0)->delete();
    }

    /**
     * Checking number of user deposits
     */
    public function testUserDeposits(): void
    {
        $this->assertIsObject($this->activeDepositRepository->userDeposits(1));
    }
}
