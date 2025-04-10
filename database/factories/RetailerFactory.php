<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class RetailerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(asText: true),
            'url' => $this->faker->url(),
            'currency_id' => Currency::query()->inRandomOrder()->first()->id,
        ];
    }
}
