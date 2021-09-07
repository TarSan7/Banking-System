<?php

namespace Tests\Unit\Repositories;

use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\Loan;
use App\Models\User;
use App\Models\UserCard;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\LoanRepository;
use App\Repository\Eloquent\TransferRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Repository\Eloquent\UserRepository;
use App\Services\CardService;
use Database\Factories\CardFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Collection\Collection;
use Tests\TestCase;

class CardServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CardService
     */
    private $cardService;
    private $mockCardRepository;
    private $mockUserCardRepository;
    private $mockUserRepository;
    private $mockTransferRepository;
    private $mockLoanRepository;
    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->mockCardRepository = $this->createMock(CardRepository::class);
        $this->mockUserCardRepository = $this->createMock(UserCardRepository::class);
        $this->mockUserRepository = $this->createMock(UserRepository::class);
        $this->mockTransferRepository = $this->createMock(TransferRepository::class);
        $this->mockLoanRepository = $this->createMock(LoanRepository::class);

        $this->cardService = new CardService(
            $this->mockCardRepository,
            $this->mockUserCardRepository,
            $this->mockUserRepository,
            new CardFactory(),
            $this->mockTransferRepository,
            $this->mockLoanRepository,
        );
    }

    /**
     * Getting card by id
     */
    public function testGetCardById(): void
    {
        $this->mockCardRepository->method('find')->willReturn(Card::first());

        $this->assertIsObject($this->cardService->getCardById(1));
    }

    /**
     * Card existing
     */
    public function testCardExists(): void
    {
        $this->mockCardRepository->method('cardExist')->willReturn(true);
        $this->assertTrue($this->cardService->cardExist());
    }

    /**
     * Getting card by number
     */
    public function testGetCardByNum(): void
    {
        $this->mockCardRepository->method('getCardByNum')->willReturn(Card::first());

        $this->assertIsObject($this->cardService->getCardByNum('0000000000000000'));
    }

    /**
     * Card added to user
     */
    public function testCardAdded(): void
    {
        $this->mockCardRepository->method('getId')->willReturn(1);
        $this->mockUserCardRepository->method('cards')->willReturn(false);

        $this->cardService->setCard(['number' => '0000000000000000']);

        $this->assertFalse($this->cardService->cardAdded());
    }

    /**
     * Adding card to user
     */
    public function testCreateCard(): void
    {
        $this->mockUserCardRepository->method('create')->willReturn(new UserCard);

        $this->assertTrue($this->cardService->createCard());
    }

    /**
     * Getting user cards
     */
    public function testGetUserCards(): void
    {
        $this->mockUserRepository->method('getCards')->willReturn(new \Illuminate\Support\Collection());
        $this->mockCardRepository->method('findAll')->willReturn(new \Illuminate\Support\Collection());

        $this->assertCount(0, $this->cardService->getUserCards());
    }

    public function testNewCreditCard(): void
    {
        $this->assertTrue((bool) $this->cardService->check());

//        $this->mockUserCardRepository->method('cardIdByUser')->willReturn([]);
//        $this->mockCardRepository->method('credit')->willReturn([]);
//        $this->mockCardRepository->method('create')->willReturn(new Card);
//        $this->mockTransferRepository->method('create')->willReturn(new CardTransfer);
//        $this->mockUserCardRepository->method('createNew')->willReturn(new UserCard);

        $this->mockLoanRepository->method('getLoan')->willReturn(Loan::first());
        $this->mockCardRepository->method('getCardByNum')->willReturn(Card::first());


        $newCard = $this->cardService->newCreditCard(-101, 1);
        $this->assertIsObject($newCard);
        $this->assertEquals($newCard, $this->cardService->newCreditCard(-101, 1));
        $this->assertTrue((bool) $this->cardService->check());
    }
}
