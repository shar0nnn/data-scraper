<?php

namespace Database\Factories;

use App\Models\ScrapedProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScrapedImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'scraped_product_id' => ScrapedProduct::query()->inRandomOrder()->first()->id,
            'url' => $this->faker->imageUrl(),
            'file_name' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
