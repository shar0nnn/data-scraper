<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->createMany([[
            'name' => 'SuperUser',
            'email' => config('settings.users.admin.email'),
            'password' => 'admin123',
            'role_id' => Role::query()->where('name', RoleEnum::ADMIN)->first()->id,
        ], [
            'name' => 'Parser',
            'email' => config('settings.users.parser.email'),
            'password' => 'parser123',
            'role_id' => Role::query()->where('name', RoleEnum::USER)->first()->id,
        ]]);
    }
}
