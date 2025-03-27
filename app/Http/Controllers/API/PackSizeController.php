<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PackSize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PackSizeController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->jsonResponse('List of pack sizes', PackSize::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return $this->jsonResponse(
            'Pack size created successfully.',
            PackSize::query()->create($validatedData),
        );
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['string', 'max:255'],
        ]);

        $packSize = PackSize::query()->find($id);
        if (!$packSize) {
            return $this->jsonResponse('Pack size not found.', status: 404);
        }

        $packSize->update($validatedData);

        return $this->jsonResponse(
            'Pack size updated successfully.',
            $packSize,
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $packSize = PackSize::query()->find($id);
        if (!$packSize) {
            return $this->jsonResponse('Pack size not found.', status: 404);
        }

        if ($packSize->products()->exists()) {
            return $this->jsonResponse('You can not delete a used pack size.', status: 403);
        }
        $packSize->delete();

        return $this->jsonResponse('Pack size deleted successfully.');
    }
}
