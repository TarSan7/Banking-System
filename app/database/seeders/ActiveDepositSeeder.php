<?php

namespace Database\Seeders;

use App\Models\ActiveDeposit;
use Illuminate\Database\Seeder;

class ActiveDepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActiveDeposit::factory(1)->create([
            'deposit_id' => 1,
            'sum' => 1000,
            'total_sum' => 1300,
            'currency' => 'EUR',
            'month_pay' => 300,
            'duration' => 12,
            'month_left' => 12,
            'early_percent' => 1,
            'intime_percent' => 0,
            'card_id' => 1,
            'user_id' => 1,
            'date' => date('y-m-d')
        ]);
    }
}
