<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\CurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->jsonResponse(
            'List of currencies',
            CurrencyResource::collection(Currency::all())
        );
    }

    public function store(CurrencyRequest $request): JsonResponse
    {
        return $this->jsonResponse(
            'Currency created successfully.',
            new CurrencyResource(Currency::query()->create($request->validated()))
        );
    }

    public function update(CurrencyRequest $request, string $id): JsonResponse
    {
        $currency = Currency::query()->find($id);

        if (! $currency) {
            return $this->jsonResponse(
                'Currency not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        $currency->update($request->validated());

        return $this->jsonResponse(
            'Currency updated successfully.',
            new CurrencyResource($currency)
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $currency = Currency::query()->find($id);

        if (! $currency) {
            return $this->jsonResponse(
                'Currency not found.',
                status: Response::HTTP_NOT_FOUND
            );
        }

        if ($currency->retailers()->exists()) {
            return $this->jsonResponse(
                'You can not delete a used currency.',
                status: Response::HTTP_FORBIDDEN
            );
        }

        $currency->delete();

        return $this->jsonResponse('Currency deleted successfully.');
    }
}
