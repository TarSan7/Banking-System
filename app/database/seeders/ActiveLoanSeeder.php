<?php

namespace Database\Seeders;

use App\Models\ActiveLoan;
use Illuminate\Database\Seeder;

class ActiveLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActiveLoan::factory(1)->create([
            'loan_id' => 1,
            'sum' => 1200,
            'total_sum' => 200,
            'month_pay' => 20,
            'month_left' => 10,
            'card_id' => 1,
            'user_id' => 1
        ]);
    }
}
