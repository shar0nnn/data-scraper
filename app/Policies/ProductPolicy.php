<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductPolicy
{
    public function __construct(private Request $request)
    {
    }

    public function create(User $user): bool
    {
        return $this->belongsToRequestedRetailers($user);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->belongsToRequestedRetailers($user) && $this->belongsToProduct($user, $product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->belongsToProduct($user, $product);
    }

    private function belongsToRequestedRetailers(User $user): bool
    {
        $requestedRetailers = collect($this->request->input('retailers'))->pluck('id')->toArray();
        $userRetailers = $user->retailers->pluck('id')->toArray();

        return empty(array_diff($requestedRetailers, $userRetailers));
    }

    private function belongsToProduct(User $user, Product $product): bool
    {
        return $product->users->pluck('id')->contains($user->id);
    }
}
