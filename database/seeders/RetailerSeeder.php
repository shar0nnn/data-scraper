<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Image;
use App\Models\Retailer;

class RetailerSeeder extends MainSeeder
{
    public function run(): void
    {
        $currencies = Currency::query()->pluck('id');
        for ($i = 0; $i < 10; $i++) {
            $retailer = Retailer::query()->create([
                'title' => $this->faker->unique()->words(asText: true),
                'url' => $this->faker->url(),
                'currency_id' => $currencies->random(),
            ]);

            Image::query()->create([
                'imageable_id' => $retailer->id,
                'imageable_type' => Retailer::class,
                'link' => $this->faker->url(),
            ]);
        }
    }
}
