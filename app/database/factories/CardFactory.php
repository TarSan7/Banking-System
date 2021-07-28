<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Card::class;


    private $currency = array('UAH', 'EUR', 'USD', 'RUR', 'GBP', 'PLN');
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->unique()->creditCardNumber,
            'cvv' => rand(100, 999),
            'expires_end' => $this->faker->creditCardExpirationDate(),
            'sum' => rand(0., 1000000.),
            'currency' => $this->currency[rand(0, 5)]
        ];
    }
}
