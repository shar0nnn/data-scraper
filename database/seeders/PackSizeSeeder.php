<?php

namespace Database\Seeders;

use App\Models\PackSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackSizeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        PackSize::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $packSizes = [
            ['name' => 'case'],
            ['name' => 'each'],
            ['name' => 'set'],
            ['name' => 'box'],
            ['name' => 'piece'],
            ['name' => 'container'],
        ];

        foreach ($packSizes as $packSize) {
            PackSize::query()->create($packSize);
        }
    }
}
