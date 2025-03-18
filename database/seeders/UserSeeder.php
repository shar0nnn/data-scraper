<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'SuperUser',
            'email' => 'admin@gmail.com',
            'password' => 'admin123'
        ]);
    }
}
