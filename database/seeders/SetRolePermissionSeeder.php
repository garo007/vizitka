<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'full-access',
                'guard_name' => 'client',
            ],
            [
                'name' => 'buyer',
                'guard_name' => 'client',
            ],
            [
                'name' => 'seller',
                'guard_name' => 'client',
            ],
        ];

        Role::query()->insert($roles);

        $permissions = [
            [
                'name' => 'full-access',
                'guard_name' => 'client',
            ],
            [
                'name' => 'air-ticket',
                'guard_name' => 'client',
            ],
            [
                'name' => 'hotels',
                'guard_name' => 'client',
            ],
            [
                'name' => 'packages',
                'guard_name' => 'client',
            ],
            [
                'name' => 'transfers',
                'guard_name' => 'client',
            ],
            [
                'name' => 'car-rental',
                'guard_name' => 'client',
            ],
            [
                'name' => 'excursion',
                'guard_name' => 'client',
            ],
        ];

        Permission::query()->insert($permissions);
    }
}
