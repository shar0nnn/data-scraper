<?php

namespace App\Services;

use App\Filters\ScrapedProductFilter;
use App\Models\Retailer;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RetailerService
{
    public function store(array $data): Retailer|false
    {
        $link = null;

        try {
            DB::beginTransaction();
            $retailer = Retailer::query()->create($data);
            $link = $data['logo']->store(Retailer::LOGO_PATH, 'public');
            $retailer->logo()->create([
                'link' => $link,
            ]);
            DB::commit();

            return $retailer;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['retailers'])->error($throwable->getMessage());
            if ($link) {
                Storage::disk('public')->delete($link);
            }

            return false;
        }
    }

    public function update(array $data, Retailer $retailer): Retailer|false
    {
        $oldLink = $retailer->logo->link;
        $newLink = null;

        try {
            DB::beginTransaction();
            $retailer->update($data);

            if (isset($data['logo'])) {
                $newLink = $data['logo']->store(Retailer::LOGO_PATH, 'public');
                $retailer->logo()->update(['link' => $newLink]);
                Storage::disk('public')->delete($oldLink);
            }
            DB::commit();

            return $retailer;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['retailers'])->error($throwable->getMessage());
            if ($newLink) {
                Storage::disk('public')->delete($newLink);
            }
            $retailer->logo()->update(['link' => $oldLink]);

            return false;
        }
    }

    public function destroy(Retailer $retailer): bool
    {
        try {
            DB::beginTransaction();
            $link = $retailer->logo->link;
            $retailer->logo()->delete();
            $retailer->scrapedProducts->each(function ($scrapedProduct) {
                $scrapedProduct->scrapedImages()->delete();
            });
            $retailer->scrapedProducts()->delete();
            $retailer->products()->detach();
            $retailer->delete();
            DB::commit();
            Storage::disk('public')->delete($link);

            return true;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['retailers'])->error($throwable->getMessage());

            return false;
        }
    }

    public function metrics(ScrapedProductFilter $scrapedProductFilter): Builder
    {
        $queryBuilder = DB::table('scraped_products')
            ->selectRaw('
        retailer_id,
        retailers.title as retailer_title,
        ROUND(AVG(price), 2) as average_price,
        ROUND(
            SUM(
                (
                    (CAST(JSON_EXTRACT(rating, "$.\"1\"") AS DECIMAL(10,2)) * 1) +
                    (CAST(JSON_EXTRACT(rating, "$.\"2\"") AS DECIMAL(10,2)) * 2) +
                    (CAST(JSON_EXTRACT(rating, "$.\"3\"") AS DECIMAL(10,2)) * 3) +
                    (CAST(JSON_EXTRACT(rating, "$.\"4\"") AS DECIMAL(10,2)) * 4) +
                    (CAST(JSON_EXTRACT(rating, "$.\"5\"") AS DECIMAL(10,2)) * 5)
                ) /
                NULLIF(
                    CAST(JSON_EXTRACT(rating, "$.\"1\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"2\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"3\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"4\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"5\"") AS DECIMAL(10,2)), 0
                ) *
                (
                    CAST(JSON_EXTRACT(rating, "$.\"1\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"2\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"3\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"4\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"5\"") AS DECIMAL(10,2))
                )
            )
            /
            NULLIF(
                SUM(
                    CAST(JSON_EXTRACT(rating, "$.\"1\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"2\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"3\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"4\"") AS DECIMAL(10,2)) +
                    CAST(JSON_EXTRACT(rating, "$.\"5\"") AS DECIMAL(10,2))
                ), 0
            ), 2
        ) as average_rating,
        ROUND(AVG(count_images_table.scraped_product_average_number_of_images), 1) as average_number_of_images')
            ->join('retailers', 'scraped_products.retailer_id', '=', 'retailers.id')
            ->leftJoin(DB::raw('(
        SELECT scraped_product_id, COUNT(id) AS scraped_product_average_number_of_images
        FROM scraped_images
        GROUP BY scraped_product_id
        ) AS count_images_table'), 'scraped_products.id', '=', 'count_images_table.scraped_product_id')
            ->groupBy('retailer_id');

        $queryBuilder = $scrapedProductFilter->apply($queryBuilder);

        if (isset($scrapedProductFilter->appliedFilters['start_date']) || isset($scrapedProductFilter->appliedFilters['end_date'])) {
            $queryBuilder
                ->addSelect(DB::raw('DATE_FORMAT(scraping_sessions.created_at, "%d.%m.%Y") AS scraped_at'))
                ->groupBy('scraping_sessions.id');
        }

        return $scrapedProductFilter->apply($queryBuilder);
    }
}
