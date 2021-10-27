<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\CardTransfer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardTransferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CardTransfer::class;

    private $currency = array('UAH', 'EUR', 'USD', 'RUR', 'GBP', 'PLN');

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'card_from' => function ()
            {
                return Card::find(rand(7, 100000))->number;
            },
            'card_to' => function ()
            {
                return Card::find(rand(7, 100000))->number;
            },
            'date' => $this->faker->date(),
            'sum' => rand(10, 10000),
            'new_sum' => rand(10, 10000),
            'currency' => $this->currency[rand(0, 5)],
            'comment' => 'Transfer to card',
            'user_id' => rand(1, 500)
        ];
    }
}
