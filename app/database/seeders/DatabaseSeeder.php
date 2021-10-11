<?php

namespace Database\Seeders;

use App\Models\ActiveDeposit;
use App\Models\ActiveLoan;
use App\Models\Card;
use App\Models\CardTransfer;
use App\Models\User;
use App\Models\UserCard;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        Card::factory(1100000)->create();
//        User::factory(10)->create();
//        UserCard::factory(150000)->create();
//        CardTransfer::factory(100000)->create();
        ActiveDeposit::factory(200000)->create();
//        ActiveLoan::factory(200000)->create();
    }
}
