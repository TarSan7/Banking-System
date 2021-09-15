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
            'loan_id' => 1,
            'sum' => 1200,
            'total_sum' => 1250,
            'month_pay' => 100,
            'month_left' => 12,
            'card_id' => 1,
            'user_id' => 1
        ];
    }
}
