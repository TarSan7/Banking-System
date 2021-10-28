<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use App\Models\UserCard;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Repository\Eloquent\UserRepository;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Object_;
use Tests\TestCase;

class CardTransferControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var UserRepository
     * @var CardRepository
     * @var UserCardRepository
     */
    private $cardRepository, $mockUserCardRepository, $transferService, $user;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(UserSeeder::class);
        $this->mockUserCardRepository = $this->createMock(UserCardRepository::class);
        $this->cardRepository = new CardRepository(new Card());
        $this->cardRepository->create([
            'type' => 'checking',
            'number' => '10101010101010',
            'cvv' => '657',
            'expires_end' => date('y-m-d'),
            'sum' => 100,
            'currency' => 'EUR'
        ]);
        $this->user = User::factory()->create();
    }

    public function test_make()
    {
//        $this->mockUserCardRepository->method('cardIdByUser')->willReturn();
        $data = [
            'numberFrom' => 1,
            'numberTo' => '0000000000000002',
            'sum' => 100,
            'comment' => 'Transfer to card'
        ];
        $this->json('POST', 'api/en/cardTransfer', $data)
            ->assertStatus(200)
            ->assertJson([
                'error' => 'Unauthorized'
            ]);

        $data['numberFrom'] = 3;
        $this->actingAs($this->user)->json('POST', 'api/en/cardTransfer', $data)
            ->assertStatus(200)
            ->assertJson([
                'error' => 'No such cards'
            ]);

//        $data['numberFrom'] = 7;
//        $this->actingAs($this->user)->json('POST', 'api/en/cardTransfer', $data)
//            ->assertStatus(200)
//            ->assertJson([
//                'error' => 'No such cards'
//            ]);
    }
}
