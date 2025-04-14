<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
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

        Role::query()->insert($locations);
    }
}
