<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->createMany([[
            'name' => 'SuperUser',
            'email' => config('settings.users.admin.email'),
            'password' => 'admin123'
        ], [
            'name' => 'Parser',
            'email' => config('settings.users.parser.email'),
            'password' => 'parser123'
        ]]);
    }
}
