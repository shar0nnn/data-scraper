<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::query()->where('email', config('settings.users.admin.email'))->exists()) {
            User::factory()->create([
                'name' => 'SuperUser',
                'email' => config('settings.users.admin.email'),
                'password' => 'admin123'
            ]);
        }

        if (!User::query()->where('email', config('settings.users.parser.email'))->exists()) {
            User::factory()->create([
                'name' => 'Parser',
                'email' => config('settings.users.parser.email'),
                'password' => 'parser123'
            ]);
        }
    }
}
