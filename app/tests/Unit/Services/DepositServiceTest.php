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
use Illuminate\Support\Arr;
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
        $this->assertCount(Deposit::all()->count(), $this->depositService->getBaseDeposits());
    }

    /**
     * Finding one deposit
     */
    public function testOneDeposit(): void
    {
        $this->assertEquals(Deposit::find(1), $this->depositService->oneDeposit(1));
    }

    public function testAccept(): void
    {
        $arr = $this->depositService->accept([
            'currency' => 'EUR',
            'sum' => 198,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        ], 1);
        $this->assertEquals('success', Arr::get($arr, 0, null));

        $arrError = $this->depositService->accept([
            'currency' => 'UAH',
            'sum' => 198,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        ], 1);
        $this->assertEquals('Different currencies.', Arr::get($arrError, 1, null));

        $arrSecError = $this->depositService->accept([
            'currency' => 'EUR',
            'sum' => 10000000000,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        ], 1);
        $this->assertEquals('error', Arr::get($arrSecError, 0, null));

        $arrFormErrorFirst = $this->depositService->accept([
            'currency' => 'EUR',
            'sum' => 100,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        ], 1);

        $arrFormErrorSec= $this->depositService->accept([
            'currency' => 'EUR',
            'sum' => 100,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        ], 1);
        $arrFormErrorThird= $this->depositService->accept([
            'currency' => 'EUR',
            'sum' => 100,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        ], 1);
        $this->assertEquals('error', Arr::get($arrFormErrorThird, 0, null));

    }

    public function testCountUserDeposits(): void
    {
        $this->assertCount($this->depositService->countUserDeposits(), $this->depositService->getUserDeposits());
    }

    public function testGetUserDeposits(): void
    {
        $userDeposits = $this->depositService->getUserDeposits();
        $this->assertCount(3, $userDeposits);

        $first = Arr::get($userDeposits, 0, null);
        $this->assertEquals('Junior', Arr::get($first, 'title', null));

        foreach ($userDeposits as $one) {
            $result = $this->depositService->close(Arr::get($one, 'id', null));
            $this->assertEquals('success', Arr::get($result,0, null));
        }
    }

}
