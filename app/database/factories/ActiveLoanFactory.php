<?php

namespace Database\Factories;

use App\Models\ActiveLoan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveLoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveLoan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'loan_id' => rand(1, 5),
            'sum' => rand(100, 5000),
            'total_sum' => rand(100, 5000),
            'month_pay' => rand(1, 1000),
            'month_left' => rand(0, 24),
            'card_id' => rand(1, 1100000),
            'user_id' => rand(1, 100000)
        ];
    }

    public function date()
    {
        return $this->faker->dateTime;
    }
}
