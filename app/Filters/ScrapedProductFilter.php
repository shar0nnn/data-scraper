<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ScrapedProductFilter extends QueryFilters
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    private function explodeValues(string $values): array
    {
        return array_filter(explode(',', $values));
    }

    public function retailer_ids($term): void
    {
        $this->builder->whereIn('retailer_id', $this->explodeValues($term));
    }

    public function product_ids($term): void
    {
        $this->builder->whereIn('product_id', $this->explodeValues($term));
    }

    public function manufacturer_part_numbers($term): void
    {
        $this->builder->whereHas('product', function ($query) use ($term) {
            $query->whereIn('manufacturer_part_number', $this->explodeValues($term));
        });
    }

    public function start_date($term): void
    {
        $this->builder->whereDate('created_at', '>=', $term);
    }

    public function end_date($term): void
    {
        $this->builder->whereDate('created_at', '<=', $term);
    }
}
