<?php

namespace Database\Seeders;

use App\Models\ScrapedImage;
use App\Models\ScrapedProduct;
use Illuminate\Support\Facades\DB;

class ScrapedImageSeeder extends MainSeeder
{
    public function run(): void
    {
        $retailersProducts = DB::table('product_retailer')->pluck('id');
        $scrapedImages = [];

        foreach ($retailersProducts as $retailerProduct) {
            $productImageCount = rand(0, 10);

            for ($i = 0; $i < $productImageCount; $i++) {
                $scrapedImages[] = [
                    'product_retailer_id' => $retailerProduct,
                    'content_hash' => md5($this->faker->unique()->sentence()),
                    'source_url' => $this->faker->imageUrl(),
                    'local_url' => $this->faker->imageUrl(),
                    'alt' => $this->faker->word(),
                    'first_scraped_at' => now(),
                    'last_scraped_at' => now()->addDays(365),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        };

        foreach (array_chunk($scrapedImages, 1000) as $chunk) {
            ScrapedImage::query()->insert($chunk);
        }
    }
}
