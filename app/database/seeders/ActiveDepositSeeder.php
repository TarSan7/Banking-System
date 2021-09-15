<?php

namespace Database\Seeders;

use App\Models\ActiveDeposit;
use Database\Factories\ActiveDepositFactory;
use Faker\Factory;
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
        ActiveDeposit::factory(1)->create();
    }
}
