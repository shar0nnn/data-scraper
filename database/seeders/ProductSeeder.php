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

        $products = Product::factory()
            ->count(1000)
            ->state(fn() => ['pack_size_id' => $packSizes->random()])
            ->raw();

        Product::query()->insert($products);

        $products = Product::query()->pluck('id');

        $images = $products->map(function ($product) {
            return Image::factory()->raw([
                'imageable_id' => $product,
                'imageable_type' => Product::class,
            ]);
        })->toArray();

        Image::query()->insert($images);
    }
}
