<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\StoreCurrencyRequest;
use App\Http\Requests\Currency\UpdateCurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => CurrencyResource::collection(Currency::all())]);
    }

    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Currency created successfully.',
            'data' => new CurrencyResource(Currency::query()->create($request->validated())),
        ]);
    }

    public function update(UpdateCurrencyRequest $request, string $id): JsonResponse
    {
        $currency = Currency::query()->find($id);
        if (!$currency) {
            return response()->json(['message' => 'Currency not found.'], 404);
        }

        $currency->update($request->validated());

        return response()->json([
            'message' => 'Currency updated successfully.',
            'data' => new CurrencyResource($currency),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $currency = Currency::query()->find($id);
        if (!$currency) {
            return response()->json(['message' => 'Currency not found.'], 404);
        }

        if ($currency->retailers()->exists()) {
            return response()->json(['message' => 'You can not delete a used currency.']);
        }
        $currency->delete();

        return response()->json(['message' => 'Currency deleted successfully.']);
    }
}
