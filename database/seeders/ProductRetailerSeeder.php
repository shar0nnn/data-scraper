<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\PackSize;
use App\Models\Product;
use App\Models\Retailer;
use Illuminate\Support\Facades\DB;

class ProductRetailerSeeder extends MainSeeder
{
    public function run(): void
    {
        $data = [];
        $retailers = Retailer::query()->pluck('id');
        $products = Product::query()->pluck('id');
        foreach ($products as $product) {
            $randomNumberOfProductRetailers = $this->faker->randomElements($retailers, rand(1, 3));

            foreach ($randomNumberOfProductRetailers as $retailer) {
                $data[] = [
                    'product_id' => $product,
                    'retailer_id' => $retailer,
                    'url' => $this->faker->url(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('product_retailer')->insert($data);
    }
}
