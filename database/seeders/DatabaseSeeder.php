<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Rate;
use App\Models\User;
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
        $user = User::factory()->create([
            'name' => 'Admin',
            'surname' => 'User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'address_id' => Address::factory(1)->create()->first()->id,
        ]);

        Rate::factory(1)->create(['user_id' => $user->id]);
    }
}
