<?php

namespace App\Http\Controllers\API;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ImportProductRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Jobs\DeletePublicFile;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadeExcel;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::with(['images', 'packSize'])->paginate(10));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->store($request->validated());

        if (!$product) {
            return $this->jsonResponse('Error while creating product.', status: 503);
        }

        return $this->jsonResponse(
            'Product created successfully.',
            new ProductResource($product),
        );
    }

    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::query()->find($id);
        if (!$product) {
            return $this->jsonResponse('Product not found.', status: 404);
        }

        $product = $this->productService->update($request->validated(), $product);
        if (!$product) {
            return $this->jsonResponse('Error while updating product.', status: 503);
        }

        return $this->jsonResponse(
            'Product updated successfully.',
            new ProductResource($product->refresh()),
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $product = Product::query()->find($id);
        if (!$product) {
            return $this->jsonResponse('Product not found.', status: 404);
        }

        if (!$this->productService->destroy($product)) {
            return $this->jsonResponse('Error while deleting product.', status: 503);
        }

        return $this->jsonResponse('Product deleted successfully.');
    }

    public function import(ImportProductRequest $request): JsonResponse
    {
        $productsImport = new ProductsImport;

        try {
            FacadeExcel::import($productsImport, $request->validated('file'));
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            return $this->jsonResponse('Error while importing products.', status: 503);
        }

        return response()->json([
            'message' => __('messages.Products imported successfully.', ['number' => $productsImport->getDBRows()]),
            'meta' => [
                'file_rows' => $productsImport->getFileRows(),
                'memory_usage' => $productsImport->getMemoryUsage(),
                'execution_time' => $productsImport->getExecutionTime(),
            ]
        ]);
    }

    public function export(): JsonResponse
    {
        $productsExport = new ProductsExport;
        $productsExport->store($productsExport->fileName, 'public', Excel::XLSX);
        DeletePublicFile::dispatch($productsExport->fileName)->delay(now()->addHour());

        return $this->jsonResponse(
            'Products exported successfully.',
            Storage::temporaryUrl($productsExport->fileName, now()->addHour()),
            meta: [
                'file_rows' => $productsExport->getFileRows(),
                'memory_usage' => $productsExport->getMemoryUsage(),
                'execution_time' => $productsExport->getExecutionTime(),
            ]
        );
    }
}
