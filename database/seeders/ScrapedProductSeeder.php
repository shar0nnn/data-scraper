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
        $retailers = Retailer::query()->pluck('id');
        $products = DB::table('product_retailer')
            ->select('retailer_id', 'product_id')
            ->get()
            ->groupBy('retailer_id')
            ->map(fn($group) => $group->pluck('product_id'));
        $scrapingSessions = ScrapingSession::query()->pluck('id');
        $currentScrapingSession = 0;

        for ($i = 0; $i < 365; $i++) {
            foreach ($retailers as $retailer) {
                foreach ($products[$retailer] as $product) {
                    $scrapedProducts[] = [
                        'product_id' => $product,
                        'retailer_id' => $retailer,
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
