<?php

namespace Database\Seeders;

use App\Models\Retailer;
use App\Models\ScrapingSession;

class ScrapingSessionSeeder extends MainSeeder
{
    public function run(): void
    {
        $scrapingSessions = collect();
        $countRetailers = Retailer::query()->count();

        for ($i = 0; $i < 365; $i++) {
            for ($j = 0; $j < $countRetailers; $j++) {
                $scrapingSessions->push(
                    ScrapingSession::factory()->raw([
                        'created_at' => now()->subDays($i),
                        'updated_at' => now()->subDays($i),
                    ])
                );
            }
        }

        ScrapingSession::query()->insert($scrapingSessions->toArray());
    }
}
