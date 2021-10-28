<?php
namespace Tests\Feature;

use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\LoginUserRequest;
use App\Models\Card;
use App\Models\User;
use App\Models\UserCard;
use App\Repository\Eloquent\CardRepository;
use App\Repository\Eloquent\UserCardRepository;
use App\Repository\Eloquent\UserRepository;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed(UserSeeder::class);
        $this->user = User::factory()->create();
    }

    /**
     * User presents in database
     */
    public function test_login(): void
    {
        $userData = [
            'email' => '1@2.3',
            'password' => '0000Ts!'
        ];

        $this->json('POST', 'api/en/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);

        $this->json('POST', 'api/en/login', $userData)
            ->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized'
            ]);
        $userData = [
            'email' => 'igor@g.c',
            'password' => '1111Ij!'
        ];
        $this->json('POST', 'api/en/login', $userData)
            ->assertStatus(200);
        $this->assertAuthenticated();
    }

    public function test_register()
    {
        $userData = [
            'name' => 'Bird',
            'email' => 'birdn@g.c',
            'password' => '1111Bi!'
        ];
        $this->json('POST', 'api/en/register', $userData)
            ->assertStatus(201)
            ->assertJsonFragment([
                'message' => 'User successfully registered'
            ]);

        $this->json('POST', 'api/en/register', $userData)
            ->assertStatus(422)->assertJson([
                "email" => [
                    "The email has already been taken."
                ]
            ]);

        $userData = [
            'name' => 'Bird',
            'email' => 'bird@g.c',
            'password' => '1111Bi!'
        ];
        $this->actingAs($this->user)->json('POST', 'api/en/register', $userData)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'You logged in'
            ]);
    }

    public function test_logout()
    {
        $this->json('POST', 'api/en/logout')
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
