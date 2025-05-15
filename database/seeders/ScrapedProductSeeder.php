<?php

namespace Database\Seeders;

use App\Models\Retailer;
use App\Models\ScrapedProduct;
use App\Models\ScrapingSession;
use Illuminate\Support\Facades\DB;

class ScrapedProductSeeder extends MainSeeder
{
    public function run(): void
    {
        $scrapedProducts = [];
        $productsByRetailer = DB::table('product_retailer')
            ->select('id', 'retailer_id')
            ->get()
            ->groupBy('retailer_id')
            ->map(fn($group) => $group->pluck('id'));
        $scrapingSessions = ScrapingSession::query()->pluck('id');
        $currentScrapingSession = 0;

        for ($i = 0; $i < 365; $i++) {
            foreach ($productsByRetailer as $productIds) {
                foreach ($productIds as $productId) {
                    $scrapedProducts[] = [
                        'product_retailer_id' => $productId,
                        'price' => $this->faker->randomFloat(2, 0, 100000),
                        'stock_count' => $this->faker->numberBetween(0, 100000),
                        'rating' => json_encode([
                            1 => $this->faker->numberBetween(0, 7000),
                            2 => $this->faker->numberBetween(0, 4000),
                            3 => $this->faker->numberBetween(0, 2000),
                            4 => $this->faker->numberBetween(0, 6000),
                            5 => $this->faker->numberBetween(0, 10000),
                        ]),
                        'scraping_session_id' => $scrapingSessions[$currentScrapingSession],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];
                }
                $currentScrapingSession++;
            }

            ScrapedProduct::query()->insert($scrapedProducts);
            $scrapedProducts = [];
        }
    }
}
