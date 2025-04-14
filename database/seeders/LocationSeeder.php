<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Africa'],
            ['name' => 'Antarctica'],
            ['name' => 'Asia'],
            ['name' => 'Europe'],
            ['name' => 'North America'],
            ['name' => 'South America'],
            ['name' => 'Oceania'],
        ];

        Location::query()->insert($locations);
    }
}
