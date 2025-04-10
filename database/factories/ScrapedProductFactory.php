<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\ScrapingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScrapedProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::query()->inRandomOrder()->first(),
            'retailer_id' => Retailer::query()->inRandomOrder()->first(),
            'price' => $this->faker->randomFloat(2, 0, 1000000),
            'stock_count' => $this->faker->numberBetween(0, 100000),
            'rating' => json_encode([
                1 => $this->faker->numberBetween(0, 7000),
                2 => $this->faker->numberBetween(0, 4000),
                3 => $this->faker->numberBetween(0, 2000),
                4 => $this->faker->numberBetween(0, 6000),
                5 => $this->faker->numberBetween(0, 10000),
            ]),
            'scraping_session_id' => ScrapingSession::query()->inRandomOrder()->first(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
