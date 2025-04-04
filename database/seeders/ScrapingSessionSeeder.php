<?php

namespace Database\Seeders;

use App\Models\Retailer;
use App\Models\ScrapedProduct;
use App\Models\ScrapingSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScrapingSessionSeeder extends MainSeeder
{
    public function run(): void
    {
        $scrapingSessions = [];
        $countRetailers = Retailer::query()->count();
        for ($i = 0; $i < 365; $i++) {
            for ($j = 0; $j < $countRetailers; $j++) {
                $scrapingSessions[] = [
                    'status' => $this->faker->words(asText: true),
                    'created_at' => now()->subDays($i),
                    'updated_at' => now(),
                ];
            }
        }

        ScrapingSession::query()->insert($scrapingSessions);
    }
}
