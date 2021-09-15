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
        ActiveLoan::factory(1)->create();
    }
}
