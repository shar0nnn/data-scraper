<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\PackSize;
use App\Models\Product;

class ProductSeeder extends MainSeeder
{
    public function run(): void
    {
        $packSizes = PackSize::query()->pluck('id');
        for ($i = 0; $i < 1000; $i++) {
            $products[] = [
                'title' => $this->faker->words(asText: true),
                'description' => $this->faker->text(),
                'manufacturer_part_number' => $this->faker->unique()->numerify('###############'),
                'pack_size_id' => $packSizes->random(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }
        Product::query()->insert($products);

        $products = Product::query()->pluck('id');
        $images = [];
        foreach ($products as $product) {
            $images[] = [
                'imageable_id' => $product,
                'imageable_type' => Product::class,
                'link' => $this->faker->url(),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }
        Image::query()->insert($images);
    }
}
