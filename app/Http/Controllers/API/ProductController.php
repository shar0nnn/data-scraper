<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    )
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::with(['images', 'packSize'])->paginate());
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
}
