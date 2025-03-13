<?php

namespace Database\Seeders;

use App\Models\PackSize;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $faker = Faker::create();
        $packSizes = PackSize::query()->pluck('id');
        for ($i = 0; $i < 1000; $i++) {
            $products[] = [
                'title' => $faker->unique()->words(asText: true),
                'description' => $faker->text(),
                'manufacturer_part_number' => $faker->unique()->numerify('###############'),
                'pack_size_id' => $packSizes->random(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        Product::query()->insert($products);
    }
}
