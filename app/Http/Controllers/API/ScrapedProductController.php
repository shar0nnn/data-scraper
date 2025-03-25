<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScrapedProduct\ScrapedProductRequest;
use App\Services\ScrapedProductService;
use Illuminate\Http\JsonResponse;

class ScrapedProductController extends Controller
{
    public function store(ScrapedProductRequest $request, ScrapedProductService $scrapedProductService): JsonResponse
    {
        $scrapedProduct = $scrapedProductService->store($request->validated());
        if (!$scrapedProduct) {
            return response()->json($this->payload('Error while storing scraped product.'));
        }

        return response()->json($this->payload(
            'Scraped product stored successfully.',
            $scrapedProduct,
        ));
    }
}
