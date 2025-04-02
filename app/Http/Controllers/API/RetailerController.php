<?php

namespace App\Http\Controllers\API;

use App\Filters\ScrapedProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Retailer\StoreRetailerRequest;
use App\Http\Requests\Retailer\UpdateRetailerRequest;
use App\Http\Resources\RetailerResource;
use App\Models\Retailer;
use App\Services\RetailerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RetailerController extends Controller
{
    public function __construct(
        private RetailerService $retailerService
    )
    {
    }

    public function index(): JsonResponse
    {
        return $this->jsonResponse(
            'List of retailers',
            RetailerResource::collection(Retailer::with(['logo', 'currency'])->get())
        );
    }

    public function store(StoreRetailerRequest $request): JsonResponse
    {
        $retailer = $this->retailerService->store($request->validated());

        if (!$retailer) {
            return $this->jsonResponse('Error while creating retailer.', status: 503);
        }

        return $this->jsonResponse(
            'Retailer created successfully.',
            new RetailerResource($retailer),
        );
    }

    public function update(UpdateRetailerRequest $request, string $id): JsonResponse
    {
        $retailer = Retailer::query()->find($id);
        if (!$retailer) {
            return $this->jsonResponse('Retailer not found.', status: 404);
        }

        $retailer = $this->retailerService->update($request->validated(), $retailer);
        if (!$retailer) {
            return $this->jsonResponse('Error while updating retailer.', status: 503);
        }

        return $this->jsonResponse(
            'Retailer updated successfully.',
            new RetailerResource($retailer->refresh()),
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $retailer = Retailer::query()->find($id);
        if (!$retailer) {
            return $this->jsonResponse('Retailer not found.', status: 404);
        }

        if (!$this->retailerService->destroy($retailer)) {
            return $this->jsonResponse('Error while deleting retailer.', status: 503);
        }

        return $this->jsonResponse('Retailer deleted successfully.');
    }

    public function metrics(ScrapedProductFilter $scrapedProductFilter): JsonResponse
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

        $data = $scrapedProductFilter->apply($queryBuilder)->get();

        return $this->jsonResponse(
            data: $data,
            meta: [
                'applied_filters' => $scrapedProductFilter->appliedFilters,
            ]
        );
    }
}
