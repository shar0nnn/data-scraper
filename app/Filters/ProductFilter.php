<?php

namespace App\Filters;

use App\Actions\SplitString;
use Illuminate\Database\Eloquent\Builder;

final class ProductFilter extends QueryStringFilters
{
    public function pack_size_ids(string $values): void
    {
        $this->eloquentBuilder->whereHas('packSize', function (Builder $query) use ($values) {
            $query->whereIn('id', SplitString::handle($values));
        });
    }

    public function retailer_ids(string $values): void
    {
        // Filters input retailer IDs, keeping only those that belong to the currently authenticated user
        $filteredRetailerIds = array_intersect(
            SplitString::handle($values),
            auth()->user()->retailers()->pluck('retailers.id')->toArray()
        );

        $this->eloquentBuilder->whereHas('retailers', function (Builder $query) use ($filteredRetailerIds) {
            $query->whereIn('retailers.id', $filteredRetailerIds);
        });
    }

    public function search(string $value): void
    {
        $value = "%{$value}%";

        $this->eloquentBuilder->where(function (Builder $query) use ($value) {
            $query->whereLike('title', $value)
                ->orWhereLike('description', $value)
                ->orWhereLike('manufacturer_part_number', $value);
        });
    }
}
