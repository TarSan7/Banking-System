<?php

namespace Tests\Unit\Repositories;

use App\Models\Card;
use App\Repository\Eloquent\CardRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CardRepository
     */
    private $cardRepository;

    private $cardData = [
        'number' => '9999999999999',
        'cvv' => 111,
        'expires-end' => '10-10-2020',
    ];
    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->cardRepository = new CardRepository(new Card());
    }

    /**
     * Getting all fields of Card
     */
    public function testAll(): void
    {
        $this->assertCount(6, $this->cardRepository->all());
    }

    /**
     * Finding all Cards which Ids in array
     */
    public function testFindAll(): void
    {
        $cards = $this->cardRepository->findAll([1, 2, 3]);
        $this->assertCount(3, $cards);
    }

    /**
     * Checking if card exists
     */
    public function testCardExist(): void
    {
        $this->assertFalse($this->cardRepository->cardExist($this->cardData));
    }

    /**
     * Getting card Id by number
     */
    public function testGetId(): void
    {
        $this->assertEquals(3, $this->cardRepository->getId('0000000000000002'));
    }

    /**
     * Getting card info by number
     */
    public function testGetCardByNum(): void
    {
        $this->assertEquals('general',
            $this->cardRepository->getCardByNum('0000000000000002')['type']);
    }

    /**
     * Getting balance of card by id
     */
    public function testGetSumFrom(): void
    {
        $this->assertEquals(1000000000, $this->cardRepository->getSumFrom(5));
    }

    /**
     * Getting balance of card by id
     */
    public function testGetSumTo(): void
    {
        $this->assertEquals(1000000000, $this->cardRepository->getSumTo('0000000000000000'));
    }

    /**
     * Updating card by id
     */
    public function testUpdateFrom(): void
    {
        $this->cardRepository->updateFrom(1, ['sum' => 1]);
        $this->assertDatabaseHas('cards', [
            'sum' => 1
        ]);
    }

    /**
     * Updating card by number
     */
    public function testUpdateTo(): void
    {
        $this->cardRepository->updateTo('0000000000000000', ['sum' => 11]);
        $this->assertDatabaseHas('cards', [
            'sum' => 11
        ]);
    }

    /**
     * Getting currency of card by id
     */
    public function testGetCurrencyFrom(): void
    {
        $this->assertEquals('UAH', $this->cardRepository->getCurrencyFrom(1));
    }

    /**
     * Getting currency of card by number
     */
    public function testGetCurrencyTo(): void
    {
        $this->assertEquals('EUR', $this->cardRepository->getCurrencyTo('0000000000000001'));
    }

    /**
     * Update general sum
     */
    public function testGetGeneralCardNum(): void
    {
        $this->assertEquals('0000000000000002', $this->cardRepository->getGeneralCardNum(3));
    }

    /**
     * Updating balance of card by id
     */
    public function testUpdateSum(): void
    {
        $this->cardRepository->updateSum(6, 20);
        $this->assertDatabaseHas('cards', [
            'sum' => 999999980
        ]);
    }

    /**
     * Finding card number by its id
     */
    public function testGetNumber(): void
    {
        $this->assertEquals('0000000000000000', $this->cardRepository->getNumber(1));
    }

    /**
     * Checking if user has credit card with necessary currency
     */
    public function testCredit(): void
    {
        $this->assertNull($this->cardRepository->credit([1, 2, 3], 1));
    }

    /**
     * Checking finding user cards
     */
    public function testFind(): void
    {
        $this->assertEquals('EUR', $this->cardRepository->find(2)->currency);
    }

    /**
     * Checking sum of bank card
     */
    public function testCheckGeneralSum(): void
    {
        $this->assertTrue($this->cardRepository->checkGeneralSum(0, 'UAH'));
    }

    /**
     * Update general card
     */
    public function testUpdateGeneral(): void
    {
        $this->cardRepository->updateGeneral('EUR', ['sum' => 999.99]);
        $this->assertDatabaseHas('cards', [
            'sum' => 999.99,
            'type' => 'general'
        ]);
    }

    /**
     * Getting general cards
     */
    public function testGeneralSumByCurrency(): void
    {
        $this->assertEquals(1000000000, $this->cardRepository->generalSumByCurrency("EUR"));
    }

    /**
     * Getting general card's sum by currency
     */
    public function testGetGeneral(): void
    {
        $this->assertCount(6, $this->cardRepository->getGeneral());
    }
}
