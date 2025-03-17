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
        for ($i = 0; $i < 3650; $i++) {
            $sessionKey = Str::random();
            $scrapingSessions[] = [
                'session_key' => $sessionKey,
                'status' => $this->faker->words(asText: true),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ScrapingSession::query()->insert($scrapingSessions);
    }
}
