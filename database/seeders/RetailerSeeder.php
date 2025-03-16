<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Image;
use App\Models\Retailer;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RetailerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $currencies = Currency::query()->pluck('id');
        for ($i = 0; $i < 10; $i++) {
            $retailer = Retailer::query()->create([
                'title' => $faker->unique()->words(asText: true),
                'url' => $faker->url(),
                'currency_id' => $currencies->random(),
            ]);

            Image::query()->create([
                'imageable_id' => $retailer->id,
                'imageable_type' => Retailer::class,
                'link' => $faker->url(),
            ]);
        }
    }
}
