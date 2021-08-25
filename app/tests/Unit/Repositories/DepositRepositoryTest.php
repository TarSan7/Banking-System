<?php

namespace Tests\Unit\Repositories;

use App\Models\ActiveDeposit;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Deposit;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\DepositRepository;
use App\Repository\Eloquent\LoanRepository;
use App\Repository\Eloquent\TransferRepository;
use Illuminate\Support\Arr;
use Tests\TestCase;

class DepositRepositoryTest extends TestCase
{
    /**
     * @var LoanRepository
     */
    private $depositRepository, $activeDepositRepository;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->depositRepository = new DepositRepository(new Deposit(), new ActiveDeposit());
        $this->activeDepositRepository = new ActiveDepositRepository(
            new ActiveDeposit(),
            new CardRepository(new Card()),
            new TransferRepository(new CardTransfer())
        );
    }

    /**
     * Getting all fields of Deposit
     */
    public function testAll(): void
    {
        $this->assertEquals(Deposit::all(), $this->depositRepository->all());
    }

    /**
     * Get existing deposit by id
     */
    public function testGetDeposit(): void
    {
        $model = $this->depositRepository->getDeposit(1);
        $this->assertEquals('Junior', Arr::get($model, 'title', null));
    }

    /**
     * Creating new deposit
     */
    public function testNewDeposit(): void
    {
        if ($this->depositRepository->newDeposit(1, [
            'sum' => 199,
            'currency' => 'UAH',
            'percent' => 20,
            'duration' => 4,
            'numberFrom' => 8
        ], 0)) {
            $this->assertTrue(ActiveDeposit::where('deposit_id', 1)->where('sum', 199)
                ->where('card_id', 8)->where('user_id', 0)->exists());
            $deposit = ActiveDeposit::where('deposit_id', 1)->where('sum', 199)->where('card_id', 8)->first();
            CardTransfer::where('user_id', 0)->delete();
            $this->activeDepositRepository->delete(Arr::get($deposit, 'id', null));
        }
    }
}
