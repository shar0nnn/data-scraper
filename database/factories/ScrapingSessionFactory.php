<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ScrapingSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'status' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
