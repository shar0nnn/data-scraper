<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => RoleEnum::ADMIN,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => RoleEnum::USER,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Role::query()->insert($roles);
    }
}
