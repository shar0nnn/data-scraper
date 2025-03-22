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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RetailerController extends Controller
{
    public function __construct(
        private RetailerService $retailerService
    )
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return RetailerResource::collection(Retailer::all());
    }

    public function store(StoreRetailerRequest $request): JsonResponse
    {
        $retailer = $this->retailerService->store($request->validated());

        if (!$retailer) {
            return response()->json(['message' => "Error while creating retailer."], 503);
        }

        return response()->json([
            'message' => 'Retailer created successfully.',
            'data' => new RetailerResource($retailer),
        ]);
    }

    public function update(UpdateRetailerRequest $request, string $id): JsonResponse
    {
        $retailer = Retailer::query()->find($id);
        if (!$retailer) {
            return response()->json(['message' => 'Retailer not found.'], 404);
        }

        $retailer = $this->retailerService->update($request->validated(), $retailer);
        if (!$retailer) {
            return response()->json(['message' => 'Error while updating retailer.'], 503);
        }

        return response()->json([
            'message' => 'Retailer updated successfully.',
            'data' => new RetailerResource($retailer->refresh()),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $retailer = Retailer::query()->find($id);
        if (!$retailer) {
            return response()->json(['message' => 'Retailer not found.'], 404);
        }

        if (!$this->retailerService->destroy($retailer)) {
            return response()->json(['message' => 'Error while deleting retailer.'], 503);
        }

        return response()->json(['message' => 'Retailer deleted successfully.']);
    }

    public function metrics(ScrapedProductFilter $scrapedProductFilter): JsonResponse
    {
        $data = ScrapedProduct::query()
            ->selectRaw('retailer_id, round(avg(price), 2) as average_price')
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
            $element->average_number_of_images = round($totalNumberOfImages / $scrapedProducts->count(), 2);

            $totalVotes = $scrapedProducts->sum(fn($element) => array_sum($element->rating));
            $totalScore = $scrapedProducts->sum(fn($element) => array_sum(
                array_map(fn($stars, $votes) => $stars * $votes, array_keys($element->rating), $element->rating)
            ));
            $element->average_rating = $totalVotes > 0 ? round($totalScore / $totalVotes, 2) : 0;

            return $element;
        });

        return response()->json(['data' => $data]);
    }
}
