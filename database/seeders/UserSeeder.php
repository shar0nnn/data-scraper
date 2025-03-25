<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::query()->where('email', 'admin@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'SuperUser',
                'email' => 'admin@gmail.com',
                'password' => 'admin123'
            ]);
        }

        if (!User::query()->where('email', config('settings.parser.email'))->exists()) {
            User::factory()->create([
                'name' => 'Parser',
                'email' => config('settings.parser.email'),
                'password' => 'parser123'
            ]);
        }
    }
}
