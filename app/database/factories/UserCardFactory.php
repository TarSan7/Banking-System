<?php

namespace Database\Factories;

use App\Models\UserCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserCard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, 100000),
            'card_id' => $this->faker->unique()->numberBetween(6, 1000000)
        ];
    }
}
