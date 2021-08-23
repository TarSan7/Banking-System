<?php

namespace Tests\Unit;

use App\Models\ActiveDeposit;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Deposit;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\DepositRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Services\DepositService;
use Tests\TestCase;

class DepositServiceTest extends TestCase
{
    /**
     * @var DepositService
     */
    private $depositService;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->depositService = new DepositService(
            new DepositRepository(new Deposit(), new ActiveDeposit()),
            new ActiveDepositRepository(
                new ActiveDeposit(),
                new CardRepository(new Card()),
                new TransferRepository(new CardTransfer())
            ),
            new CardRepository(new Card()),
            new TransferRepository(new CardTransfer()),
        );
    }

    /**
     * Getting base deposits
     */
    public function testGetBaseDeposits(): void
    {
        $this->assertIsObject($this->depositService->getBaseDeposits());
    }
}
