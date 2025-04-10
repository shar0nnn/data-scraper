<?php

namespace Database\Seeders;

use App\Models\PackSize;
use Illuminate\Database\Seeder;

class PackSizeSeeder extends Seeder
{
    public function run(): void
    {
        $packSizes = [
            ['name' => 'case'],
            ['name' => 'each'],
            ['name' => 'set'],
            ['name' => 'box'],
            ['name' => 'piece'],
            ['name' => 'container'],
        ];

        PackSize::query()->insert($packSizes);
    }
}
