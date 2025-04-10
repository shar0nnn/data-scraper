<?php

namespace Database\Factories;

use App\Models\PackSize;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(asText: true),
            'description' => $this->faker->text(),
            'manufacturer_part_number' => $this->faker->unique()->numerify('###############'),
            'pack_size_id' => PackSize::query()->inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
