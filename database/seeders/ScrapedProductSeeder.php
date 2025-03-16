<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\ScrapedImage;
use App\Models\ScrapedProduct;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ScrapedProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $retailers = Retailer::query()->pluck('id');
        $products = Product::query()->get()->groupBy('retailer_id')->map->pluck('id');
        $scrapedProducts = [];

        for ($i = 0; $i < 365; $i++) {
            foreach ($retailers as $retailer) {
                $sessionKey = Str::random();
                foreach ($products[$retailer] as $product) {
                    $scrapedProducts[] = [
                        'product_id' => $product,
                        'retailer_id' => $retailer,
                        'price' => $faker->randomFloat(2, 0, 1000000),
                        'stock_count' => $faker->numberBetween(0, 100000),
                        'rating' => json_encode([
                            1 => $faker->numberBetween(0, 100000),
                            2 => $faker->numberBetween(0, 100000),
                            3 => $faker->numberBetween(0, 100000),
                            4 => $faker->numberBetween(0, 100000),
                            5 => $faker->numberBetween(0, 100000),
                        ]),
                        'session_key' => $sessionKey,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];
                }
            }

            ScrapedProduct::query()->insert($scrapedProducts);
            $scrapedProducts = [];
        }

        $scrapedProducts = ScrapedProduct::query()->pluck('id');
        $scrapedImages = [];
        for ($i = 0; $i < count($scrapedProducts); $i++) {
            $scrapedImages[] = [
                'scraped_product_id' => $scrapedProducts->random(),
                'url' => $faker->url(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($scrapedImages, 1000) as $chunk) {
            ScrapedImage::query()->insert($chunk);
        }
    }
}
