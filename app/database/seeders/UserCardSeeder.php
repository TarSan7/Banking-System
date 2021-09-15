<?php

namespace Database\Seeders;

use App\Models\UserCard;
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
        UserCard::factory(2)->create();
    }
}
