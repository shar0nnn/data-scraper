<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\PackSize;
use App\Models\Product;
use App\Models\Retailer;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $packSizes = PackSize::query()->pluck('id');
        $retailers = Retailer::query()->pluck('id');
        for ($i = 0; $i < 1000; $i++) {
            $products[] = [
                'retailer_id' => $retailers->random(),
                'url' => $faker->url(),
                'title' => $faker->words(asText: true),
                'description' => $faker->text(),
                'manufacturer_part_number' => $faker->unique()->numerify('###############'),
                'pack_size_id' => $packSizes->random(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }
        Product::query()->insert($products);

        $products = Product::query()->pluck('id');
        $images = [];
        for ($i = 0; $i < count($products); $i++) {
            $images[] = [
                'imageable_id' => $faker->unique()->randomElement($products),
                'imageable_type' => Product::class,
                'link' => $faker->url(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }
        Image::query()->insert($images);
    }
}
