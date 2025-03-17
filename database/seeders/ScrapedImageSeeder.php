<?php

namespace Database\Seeders;

use App\Models\Retailer;
use App\Models\ScrapedImage;
use App\Models\ScrapedProduct;
use App\Models\ScrapingSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScrapedImageSeeder extends MainSeeder
{
    public function run(): void
    {

        $scrapedProducts = ScrapedProduct::query()->pluck('id');
        $scrapedImages = [];
        for ($i = 0; $i < count($scrapedProducts); $i++) {
            $scrapedImages[] = [
                'scraped_product_id' => $scrapedProducts->random(),
                'url' => $this->faker->url(),
                'file_name' => $this->faker->words(asText: true),
                'updated_at' => now(),
                'created_at' => now(),
            ];
        }

        foreach (array_chunk($scrapedImages, 1000) as $chunk) {
            ScrapedImage::query()->insert($chunk);
        }
    }
}
