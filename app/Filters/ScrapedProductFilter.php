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

    private function whereDateRelation(string $relation, string $column, string $operator, string $value): void
    {
        $this->builder->whereHas($relation, function ($query) use ($column, $operator, $value) {
            $query->whereDate($column, $operator, $value);
        });
    }

    public function retailer_ids($value): void
    {
        $this->builder->whereIn('retailer_id', $this->explodeValues($value));
    }

    public function product_ids($value): void
    {
        $this->builder->whereIn('product_id', $this->explodeValues($value));
    }

    public function manufacturer_part_numbers($value): void
    {
        $this->builder->whereHas('product', function ($query) use ($value) {
            $query->whereIn('manufacturer_part_number', $this->explodeValues($value));
        });
    }

    public function start_date($value): void
    {
        $this->whereDateRelation('scrapingSession', 'created_at', '>=', $value);
    }

    public function end_date($value): void
    {
        $this->whereDateRelation('scrapingSession', 'created_at', '<=', $value);
    }
}
