<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\ScrapedProduct;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        Image::query()->truncate();

        $faker = Faker::create();
        $imageableIds = collect([
            ...Product::query()->pluck('id'),
            ...Retailer::query()->pluck('id'),
            ...ScrapedProduct::query()->pluck('id')
        ]);
        $imageableTypes = [
            Product::class,
            Retailer::class,
            ScrapedProduct::class,
        ];
        for ($i = 0; $i < count($imageableIds); $i++) {
            $images[] = [
                'imageable_id' => $faker->randomElement($imageableIds),
                'imageable_type' => $faker->randomElement($imageableTypes),
                'link' => $faker->url(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($images, 1000) as $chunk) {
            Image::query()->insert($chunk);
        }
    }
}
