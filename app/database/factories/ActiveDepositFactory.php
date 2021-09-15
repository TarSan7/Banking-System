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

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'deposit_id' => 1,
            'sum' => 1200,
            'total_sum' => 1250,
            'currency' => 'EUR',
            'month_pay' => 100,
            'duration' => 12,
            'month_left' => 12,
            'early_percent' => 0,
            'intime_percent' => 1,
            'card_id' => 1,
            'user_id' => 1
        ];
    }
}
