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
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadeExcel;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return ProductResource::collection(
            $request->user()->products()->with(['images', 'packSize'])->paginate(10)
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->store($request->validated());

        if (! $product) {
            return $this->jsonResponse(
                'Error while creating product.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse(
            'Product created successfully.',
            new ProductResource($product),
        );
    }

    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        $product = Product::query()->find($id);
        if (! $product) {
            return $this->jsonResponse(
                'Product not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        $product = $this->productService->update($request->validated(), $product);
        if (! $product) {
            return $this->jsonResponse(
                'Error while updating product.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse(
            'Product updated successfully.',
            new ProductResource($product->refresh()),
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $product = Product::query()->find($id);

        Gate::authorize('delete', $product);

        if (! $this->productService->destroy($product)) {
            return $this->jsonResponse(
                'Error while deleting product.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse('Product deleted successfully.');
    }

    public function import(ImportProductRequest $request, ProductsImport $productsImport): JsonResponse
    {
        try {
            FacadeExcel::import($productsImport, $request->validated('file'));
        } catch (ValidationException $exception) {
            return $this->jsonResponse(
                $exception->getMessage(),
                status: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            return $this->jsonResponse(
                'Error while importing products.',
                status: Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        return $this->jsonResponse(
            'Products imported successfully.',
            meta: [
                'file_rows' => $productsImport->getRowNumber(),
                'rows_stored' => $productsImport->getRowStored(),
                'rows_updated' => $productsImport->getRowUpdated(),
                'memory_usage' => $productsImport->getMemoryUsage(),
                'execution_time' => $productsImport->getExecutionTime(),
            ],
            messagePlaceholders: ['number' => $productsImport->getProductNumber()]
        );
    }

    public function export(ProductsExport $productsExport): JsonResponse
    {
        $productsExport->store($productsExport->getFileName(), 'public', Excel::XLSX);
        DeletePublicFile::dispatch($productsExport->getFileName())->delay($productsExport->getDeletionDelay());

        return $this->jsonResponse(
            'Products exported successfully.',
            Storage::temporaryUrl($productsExport->getFileName(), $productsExport->getDeletionDelay()),
            meta: [
                'file_rows' => $productsExport->getRowNumber(),
                'memory_usage' => $productsExport->getMemoryUsage(),
                'execution_time' => $productsExport->getExecutionTime(),
            ]
        );
    }
}
