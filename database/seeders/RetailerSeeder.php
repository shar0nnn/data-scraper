<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Retailer;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailerSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Retailer::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $faker = Faker::create();
        $currencies = Currency::query()->pluck('id');
        for ($i = 0; $i < 10; $i++) {
            $retailers[] = [
                'title' => $faker->unique()->words(asText: true),
                'url' => $faker->unique()->url(),
                'currency_id' => $currencies->random(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        Retailer::query()->insert($retailers);
    }
}
