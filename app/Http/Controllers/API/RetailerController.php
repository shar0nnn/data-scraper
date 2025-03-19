<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Retailer\StoreRetailerRequest;
use App\Http\Requests\Retailer\UpdateRetailerRequest;
use App\Http\Resources\RetailerResource;
use App\Models\Retailer;
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
}
