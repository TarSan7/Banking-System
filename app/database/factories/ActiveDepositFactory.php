<?php

namespace Database\Factories;

use App\Models\ActiveDeposit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveDepositFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveDeposit::class;

    private $currency = array('UAH', 'EUR', 'USD', 'RUR', 'GBP', 'PLN');

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'deposit_id' => rand(1, 5),
            'sum' => rand(100, 1000),
            'total_sum' => rand(100, 500),
            'currency' => $this->currency[rand(0, 5)],
            'month_pay' => rand(1, 1000),
            'duration' => rand(4, 24),
            'month_left' => rand(0, 24),
            'early_percent' => 1,
            'intime_percent' => 0,
            'card_id' => rand(1, 1100000),
            'user_id' => rand(1, 100000),
            'date' => $this->faker->dateTimeInInterval('-10 years', '+27 days')
        ];
    }
}
