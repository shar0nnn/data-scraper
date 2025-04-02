<?php

namespace App\Http\Controllers\API;

use App\Exports\ScrapedProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScrapedProduct\ScrapedProductRequest;
use App\Services\ScrapedProductService;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadeExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ScrapedProductController extends Controller
{
    public function store(ScrapedProductRequest $request, ScrapedProductService $scrapedProductService): JsonResponse
    {
        $scrapedProduct = $scrapedProductService->store($request->validated());
        if (!$scrapedProduct) {
            return $this->jsonResponse('Error while storing scraped product.');
        }

        return $this->jsonResponse(
            'Scraped product stored successfully.',
            $scrapedProduct,
        );
    }

    public function export(): BinaryFileResponse
    {
        return FacadeExcel::download(new ScrapedProductsExport, 'products.xlsx', Excel::XLSX);
    }
}
