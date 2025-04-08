<?php

namespace App\Http\Controllers\API;

use App\Exports\RetailersMetricsExport;
use App\Filters\ScrapedProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Retailer\StoreRetailerRequest;
use App\Http\Requests\Retailer\UpdateRetailerRequest;
use App\Http\Resources\RetailerResource;
use App\Jobs\DeletePublicFile;
use App\Models\Retailer;
use App\Services\RetailerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

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
        return $this->jsonResponse(
            data: $this->retailerService->metrics($scrapedProductFilter)->get(),
            meta: [
                'applied_filters' => $scrapedProductFilter->appliedFilters,
            ]
        );
    }

    public function exportMetrics(RetailerService $retailerService, ScrapedProductFilter $filter): JsonResponse
    {
        $retailerMetricsExport = new RetailersMetricsExport($retailerService, $filter);
        $retailerMetricsExport->store($retailerMetricsExport->getFileName(), 'public', Excel::XLSX);
        DeletePublicFile::dispatch($retailerMetricsExport->getFileName())->delay($retailerMetricsExport->getDeletionDelay());

        return $this->jsonResponse(
            'Retailers metrics exported successfully.',
            Storage::temporaryUrl($retailerMetricsExport->getFileName(), $retailerMetricsExport->getDeletionDelay()),
            meta: [
                'file_rows' => $retailerMetricsExport->getRowNumber(),
                'memory_usage' => $retailerMetricsExport->getMemoryUsage(),
                'execution_time' => $retailerMetricsExport->getExecutionTime(),
                'applied_filters' => $filter->appliedFilters
            ]
        );
    }
}
