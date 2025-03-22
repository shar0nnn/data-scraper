<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ScrapedProduct;
use App\Models\ScrapingSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ScrapedProductService
{
    public function store(array $data): ScrapedProduct|false
    {
        $scrapedProductData = $data['data']['scraped_product'];

        try {
            DB::beginTransaction();

            // Find session per retailer per day
            $currentSession = ScrapedProduct::query()
                ->where('retailer_id', $scrapedProductData['retailer_id'])
                ->whereDate('created_at', now())
                ->pluck('scraping_session_id')
                ->first();
            if (!$currentSession) {
                $newSession = ScrapingSession::query()->create([
                    'status' => Str::random()
                ]);
            }

            $scrapedProductData['product_id'] = Product::query()
                ->where('manufacturer_part_number', $data['mpn'])
                ->pluck('id')
                ->first();
            $scrapedProductData['scraping_session_id'] = $currentSession ?? $newSession->id;
            $scrapedProduct = ScrapedProduct::query()->create($scrapedProductData);

            if (isset($data['data']['scraped_images'])) {
                $scrapedProduct->scrapedImages()->createMany($data['data']['scraped_images']);
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['scrapedProducts'])->error($throwable->getMessage());

            return false;
        }

        return $scrapedProduct;
    }
}
