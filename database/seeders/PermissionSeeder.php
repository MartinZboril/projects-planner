<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Dashboard access'],
            ['name' => 'Clients access'],
            ['name' => 'Projects access'],
            ['name' => 'Tasks access'],
            ['name' => 'Tickets access'],
            ['name' => 'Users access'],
            ['name' => 'Reports access'],
            ['name' => 'Notes access'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
