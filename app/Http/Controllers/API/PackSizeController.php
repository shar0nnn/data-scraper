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
        return response()->json(['data' => PackSize::all()]);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return response()->json([
            'message' => 'Pack size created successfully.',
            'data' => PackSize::query()->create($validatedData),
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $packSize = PackSize::query()->find($id);
        if (!$packSize) {
            return response()->json(['message' => 'Pack size not found.'], 404);
        }

        $packSize->update($validatedData);

        return response()->json([
            'message' => 'Pack size updated successfully.',
            'data' => $packSize,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $packSize = PackSize::query()->find($id);
        if (!$packSize) {
            return response()->json(['message' => 'Pack size not found.'], 404);
        }

        if ($packSize->products()->exists()) {
            return response()->json(['message' => 'You can not delete a used pack size.']);
        }
        $packSize->delete();

        return response()->json(['message' => 'Pack size deleted successfully.']);
    }
}
