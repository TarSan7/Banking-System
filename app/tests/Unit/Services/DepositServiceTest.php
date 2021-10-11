<?php

namespace Tests\Unit;

use App\Models\ActiveDeposit;
use App\Models\Deposit;
use App\Repository\Eloquent\ActiveDepositRepository;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\DepositRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Services\AllTransactionsService;
use App\Services\DepositService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use \Illuminate\Support\Collection;
use Tests\TestCase;

class DepositServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var DepositService
     */
    private $depositService;
    /**
     * @var CardRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockCardRepository;
    /**
     * @var TransferRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockTransferRepository;
    /**
     * @var ActiveDepositRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockActiveDepositRepository;
    /**
     * @var DepositRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockDepositRepository;

    private $mockAllTransactionsService;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->mockCardRepository = $this->createMock(CardRepository::class);
        $this->mockTransferRepository = $this->createMock(TransferRepository::class);
        $this->mockActiveDepositRepository = $this->createMock(ActiveDepositRepository::class);
        $this->mockDepositRepository = $this->createMock(DepositRepository::class);
        $this->mockAllTransactionsService = $this->createMock(AllTransactionsService::class);

        $this->depositService = new DepositService(
            $this->mockDepositRepository,
            $this->mockActiveDepositRepository,
            $this->mockCardRepository,
            $this->mockAllTransactionsService
        );
    }

    /**
     * Getting base deposits
     */
    public function testGetBaseDeposits(): void
    {
        $this->mockDepositRepository->method('all')->willReturn(Deposit::all());
        $this->assertCount(5, $this->depositService->getBaseDeposits());
    }

    /**
     * Finding one deposit
     */
    public function testOneDeposit(): void
    {
        $this->mockDepositRepository->method('find')->willReturn(new Deposit([
            'id' => 1,
            'title' => 'Junior',
            'early_percent' => 6,
            'intime_percent' => 7,
            'min_duration' => 9,
            'max_duration' => 12,
            'max_sum' => 500000
        ]));
        $this->assertEquals('Junior', Arr::get($this->depositService->oneDeposit(1), 'title', null));
    }

    /**
     * Test accepting deposit
     */
    public function testAccept(): void
    {
        $this->mockActiveDepositRepository->method('userDeposits')
            ->willReturn(new Collection(), collect([1, 2, 3]), new Collection(), new Collection());
        $this->mockCardRepository->method('getSumTo')->willReturn( 1000000000., 1., 10000000.);
        $this->mockCardRepository->method('getId')->willReturn(2);
        $this->mockCardRepository->method('getCurrencyFrom')->willReturn('EUR');
        $this->mockAllTransactionsService->method('takeDeposit');

        $deposit = array(
            'currency' => 'EUR',
            'sum' => 198,
            'numberFrom' => '0000000000000001',
            'percent' => 6,
            'duration' => 9
        );

        $result = $this->depositService->accept($deposit, 1);
        $this->assertEquals('success', Arr::get($result, 0, null));

        $result = $this->depositService->accept($deposit, 1);
        $this->assertEquals('Too much deposits for one User!', Arr::get($result, 1, null));

        $result = $this->depositService->accept($deposit, 1);
        $this->assertEquals('Not enough money for deposit.', Arr::get($result, 1, null));

        $deposit['currency'] = 'UAH';
        $result = $this->depositService->accept($deposit, 1);
        $this->assertEquals('Different currencies.', Arr::get($result, 1, null));
    }

    /**
     * Creating an array with data about transfer
     */
    public function testCreateDepositTransfer(): void
    {
        $deposit = $this->depositService->createDepositTransfer('00000000000000', 100, 'EUR');
        $this->assertEquals('EUR', Arr::get($deposit, 'currency', null));
    }

    /**
     * Count user deposits
     */
    public function testCountUserDeposits(): void
    {
        $this->mockActiveDepositRepository->method('userDeposits')->willReturn(new Collection());
        $this->assertCount(0, $this->depositService->getUserDeposits());
    }

    /**
     * Getting user deposits
     */
    public function testGetUserDeposits(): void
    {
        $this->mockActiveDepositRepository->method('userDeposits')->willReturn(new Collection);

        $userDeposits = $this->depositService->getUserDeposits();
        $this->assertCount(0, $userDeposits);
    }

    /**
     * Test closing deposits
     */
    public function testClose(): void
    {
        $this->mockActiveDepositRepository->method('getMoney');
        $this->mockActiveDepositRepository->method('find')->willReturn(new ActiveDeposit());
        $this->mockTransferRepository->method('create');
        $this->mockActiveDepositRepository->method('delete');

        $result = $this->depositService->close(1);
        $this->assertEquals('success', Arr::get($result,0, null));
    }
}
