<?php

namespace Tests\Unit\Services;

use App\Models\Card;
use App\Models\CardTransfer;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserRepository;
use App\Services\TransferService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TransferServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TransferService
     */
    private $transferService;
    /**
     * @var CardRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockCardRepository;
    /**
     * @var TransferRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockTransferRepository;
    /**
     * @var UserRepository|mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockUserRepository;

    private $setData = array(
        'sum' => 100.5,
        'numberFrom' => 1,
        'numberTo' => '+380',
        'comment' => 'No comments'
    );

    /**
     * SutUp method
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->mockCardRepository = $this->createMock(CardRepository::class);
        $this->mockTransferRepository = $this->createMock(TransferRepository::class);
        $this->mockUserRepository = $this->createMock(UserRepository::class);

        $this->transferService = new TransferService(
            $this->mockCardRepository,
            $this->mockTransferRepository,
            $this->mockUserRepository
        );
    }

    /**
     * Getting balance of card from which made transaction
     */
    public function testGetBalanceFrom(): void
    {
        $this->mockCardRepository->method('getSumFrom')->willReturn(100.5);
        $this->assertEquals(100.5, $this->transferService->getBalanceFrom());
    }

    /**
     * Getting balance of card to which made transaction
     */
    public function testGetBalanceTo(): void
    {
        $this->mockCardRepository->method('getSumTo')->willReturn(100.5);
        $this->assertEquals(100.5, $this->transferService->getBalanceTo());
    }

    /**
     * Getting transfer sum
     */
    public function testTransferSum(): void
    {
        $this->transferService->setInfo($this->setData, 1);
        $this->assertEquals(100.5, $this->transferService->transferSum());
    }

    /**
     * Comparing cards currency
     */
    public function testCompareCurrency(): void
    {
        $this->mockCardRepository->method('getCurrencyFrom')->willReturn('UAH');
        $this->mockCardRepository->method('getCurrencyTo')->willReturn('UAH', 'EUR');

        $this->assertTrue($this->transferService->compareCurrency());
        $this->assertFalse($this->transferService->compareCurrency());

    }

    /**
     * Comparing cards numbers
     */
    public function testCompareNumbers(): void
    {
        $this->mockCardRepository->method('getId')->willReturn(1, 2);
        $this->transferService->setInfo($this->setData, 1);

        $this->assertTrue($this->transferService->compareNumbers());
        $this->assertFalse($this->transferService->compareNumbers());
    }

    /**
     * Checking phone number
     */
    public function testCheckPhoneNumber(): void
    {
        $this->transferService->setInfo($this->setData, 1);
        $this->assertFalse($this->transferService->checkPhoneNumber());
    }

    /**
     * Creating transfer
     */
    public function testCreateTransfer(): void
    {
        $this->mockTransferRepository->method('create');
        $this->mockCardRepository->method('find')->willReturn(new Card());

        $this->assertTrue($this->transferService->createTransfer());
    }

    /**
     * Getting card transfers
     */
    public function testGetCardTransfers(): void
    {
        $this->mockCardRepository->method('find')->willReturn(new Card());
        $this->mockTransferRepository->method('getCardTransactions')->willReturn(new Collection());

        $this->assertEquals(new Collection(), $this->transferService->getCardTransfers(1));
    }

    /**
     * Checking card
     */
    public function testCardCheck(): void
    {
        $this->transferService->setInfo($this->setData, 1);
        $this->mockCardRepository->method('getSumFrom')
            ->willReturn(50.0, 500.0, 500.0, 500.0, 500.0, 500.0);
        $this->mockCardRepository->method('getCurrencyFrom')->willReturn('UAH');
        $this->mockCardRepository->method('getCurrencyTo')->willReturn( 'EUR', 'UAH', 'UAH');
        $this->mockCardRepository->method('getId')->willReturn(1, 2);
        $this->mockCardRepository->method('updateTo');
        $this->mockCardRepository->method('updateFrom');
        $this->mockTransferRepository->method('create');
        $this->mockCardRepository->method('find')->willReturn(new Card());

        $result = $this->transferService->cardCheck();
        $this->assertEquals('Not enough resource!', Arr::get($result, 1, null));

        $result = $this->transferService->cardCheck();
        $this->assertEquals('Different currencies! Try another card.', Arr::get($result, 1, null));

        $result = $this->transferService->cardCheck();
        $this->assertEquals('Same cards! Try another card.', Arr::get($result, 1, null));

        $result = $this->transferService->cardCheck();
        $this->assertEquals('success', Arr::get($result, 0, null));
    }

    /**
     * Checking other parameters
     */
    public function testOtherCheck(): void
    {
        $this->transferService->setInfo($this->setData, 1);
        $this->mockCardRepository->method('getSumFrom')
            ->willReturn(50.0, 500.0, 500.0, 500.0);
        $this->mockCardRepository->method('updateTo');
        $this->mockCardRepository->method('updateFrom');
        $this->mockTransferRepository->method('create');
        $this->mockCardRepository->method('find')->willReturn(new Card());

        $result = $this->transferService->otherCheck('phone');
        $this->assertEquals('Incorrect number format!', Arr::get($result, 1, null));

        $this->setData['numberTo'] = '+380999999999';
        $this->transferService->setInfo($this->setData, 1);

        $result = $this->transferService->otherCheck('phone');
        $this->assertEquals('Not enough resource!', Arr::get($result, 1, null));

        $result = $this->transferService->otherCheck('phone');
        $this->assertEquals('success', Arr::get($result, 0, null));
    }

    /**
     * Get cards transactions
     */
    public function testGetTransactions(): void
    {
        $this->mockUserRepository->method('getCards')->willReturn(collect(Card::first()));
        $this->mockCardRepository->method('getNumber')->willReturn('0000000000000000');
        $this->mockTransferRepository->method('getCardTransactions')->willReturn(new Collection());

        $this->assertIsArray($this->transferService->getTransactions());
    }
}
