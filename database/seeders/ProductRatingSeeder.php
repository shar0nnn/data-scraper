<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Retailer;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductRatingSeeder extends Seeder
{
    public function run(): void
    {
        ProductRating::query()->truncate();

        $faker = Faker::create();
        $products = Product::query()->pluck('id');
        $retailers = Retailer::query()->pluck('id');
        for ($i = 0; $i < count($products); $i++) {
            $productRatings[] = [
                'product_id' => $faker->unique()->randomElement($products),
                'retailer_id' => $retailers->random(),
                'rating' => json_encode([
                    1 => $faker->randomNumber(),
                    2 => $faker->randomNumber(),
                    3 => $faker->randomNumber(),
                    4 => $faker->randomNumber(),
                    5 => $faker->randomNumber(),
                ]),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        ProductRating::query()->insert($productRatings);
    }
}
