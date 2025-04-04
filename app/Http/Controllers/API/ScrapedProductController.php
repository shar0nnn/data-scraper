<?php

namespace App\Http\Controllers\API;

use App\Exports\ScrapedProductsExport;
use App\Filters\ScrapedProductExportFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScrapedProduct\ScrapedProductRequest;
use App\Jobs\DeletePublicFile;
use App\Services\ScrapedProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

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

    public function export(ScrapedProductExportFilter $filter): JsonResponse
    {
        $scrapedProductExport = new ScrapedProductsExport($filter);
        $scrapedProductExport->store($scrapedProductExport->fileName, 'public', Excel::XLSX);
        DeletePublicFile::dispatch($scrapedProductExport->fileName)->delay(now()->addHour());

        return $this->jsonResponse(
            'Scraped products exported successfully.',
            Storage::temporaryUrl($scrapedProductExport->fileName, now()->addHour()),
            meta: [
                'file_rows' => $scrapedProductExport->getFileRows(),
                'memory_usage' => $scrapedProductExport->getMemoryUsage(),
                'execution_time' => $scrapedProductExport->getExecutionTime(),
            ]
        );
    }
}
