<?php

namespace Database\Seeders;

use App\Models\ScrapedImage;
use App\Models\ScrapedProduct;

class ScrapedImageSeeder extends MainSeeder
{
    public function run(): void
    {
        $scrapedProducts = ScrapedProduct::query()->pluck('id')->toArray();

        $generator = function () use ($scrapedProducts) {
            foreach ($scrapedProducts as $scrapedProduct) {
                yield [
                    'scraped_product_id' => $scrapedProduct,
                    'url' => $this->faker->imageUrl(),
                    'file_name' => $this->faker->word(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        };

        $chunks = array_chunk(iterator_to_array($generator()), 5000);

        foreach ($chunks as $chunk) {
            ScrapedImage::query()->insert($chunk);
        }
    }
}
