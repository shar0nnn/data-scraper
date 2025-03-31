<?php

namespace App\Http\Controllers\API;

use App\Filters\ScrapedProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Retailer\StoreRetailerRequest;
use App\Http\Requests\Retailer\UpdateRetailerRequest;
use App\Http\Resources\RetailerResource;
use App\Models\Retailer;
use App\Models\ScrapedProduct;
use App\Services\RetailerService;
use Illuminate\Http\JsonResponse;

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
        $data = ScrapedProduct::query()
            ->selectRaw('retailer_id, retailers.title as retailer_title, round(avg(price), 2) as average_price')
            ->join('retailers', 'scraped_products.retailer_id', '=', 'retailers.id')
            ->filter($scrapedProductFilter)
            ->groupBy('retailer_id')
            ->get();

        $data = $data->map(function ($element) use ($scrapedProductFilter) {
            $scrapedProducts = ScrapedProduct::query()
                ->filter($scrapedProductFilter)
                ->where('retailer_id', $element->retailer_id)
                ->withCount('scrapedImages')
                ->get();

            $totalNumberOfImages = $scrapedProducts->sum('scraped_images_count');
            $element->average_number_of_images = $scrapedProducts->count() > 0
                ? round($totalNumberOfImages / $scrapedProducts->count(), 2)
                : 0;

            $totalVotes = $scrapedProducts->sum(fn($element) => array_sum($element->rating));
            $totalScore = $scrapedProducts->sum(fn($element) => array_sum(
                array_map(fn($stars, $votes) => $stars * $votes, array_keys($element->rating), $element->rating)
            ));
            $element->average_rating = $totalVotes > 0
                ? round($totalScore / $totalVotes, 2)
                : 0;

            return $element;
        });

        return $this->jsonResponse(
            data: $data,
            meta: [
                'applied_filters' => $scrapedProductFilter->appliedFilters,
            ]
        );
    }
}
