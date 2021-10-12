<?php

namespace Database\Seeders;

use App\Models\UserCard;
use Database\Factories\UserCardFactory;
use Illuminate\Database\Seeder;

class UserCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCard::factory(1)->create([
            'user_id' => 1,
            'card_id' => 1
        ]);
    }
}
