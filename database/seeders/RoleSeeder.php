<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Boss', 'is_active' => true, 'is_primary' => true]);
        Role::create(['name' => 'Manager', 'is_active' => true, 'is_primary' => true]);
        Role::create(['name' => 'Employee', 'is_active' => true, 'is_primary' => true]);
    }
}
