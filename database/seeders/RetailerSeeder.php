<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Retailer;

class RetailerSeeder extends MainSeeder
{
    public function run(): void
    {
        Retailer::factory()
            ->count(10)
            ->create()
            ->each(function ($retailer) {
                Image::factory()->create([
                    'imageable_id' => $retailer->id,
                    'imageable_type' => Retailer::class,
                    'link' => $this->faker->imageUrl(),
                ]);
            });
    }
}
