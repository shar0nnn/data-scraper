<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\ScrapedProduct;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ScrapedProductSeeder extends Seeder
{
    public function run(): void
    {
        ScrapedProduct::query()->truncate();

        $faker = Faker::create();
        $products = Product::query()->pluck('id');
        $retailers = Retailer::query()->pluck('id');
        for ($i = 0; $i < count($products) * count($retailers); $i++) {
            $scrapedProducts[] = [
                'product_id' => $products->random(),
                'retailer_id' => $retailers->random(),
                'price' => $faker->randomFloat(2),
                'stock_count' => $faker->randomNumber(),
                'scraped_at' => Carbon::instance($faker->dateTimeBetween('-1 year'))->toDateTimeString(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($scrapedProducts, 1000) as $chunk) {
            ScrapedProduct::query()->insert($chunk);
        }
    }
}
