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

    public function retailer_ids($term)
    {
        if ($term) {
            $retailers = explode(',', $term);

            return $this->builder->whereIn('retailer_id', $retailers);
        }
    }

    public function product_ids($term)
    {
        if ($term) {
            $products = explode(',', $term);

            return $this->builder->whereIn('product_id', $products);
        }
    }

    public function manufacturer_part_numbers($term)
    {
        if ($term) {
            $mpns = explode(',', $term);
            return $this->builder->whereHas('product', function ($query) use ($mpns) {
                return $query->whereIn('manufacturer_part_number', $mpns);
            });
        }
    }

    public function start_date($term)
    {
        if ($term) {
            return $this->builder->whereDate('created_at', '>=', $term);
        }
    }

    public function end_date($term)
    {
        if ($term) {
            return $this->builder->whereDate('created_at', '<=', $term);
        }
    }
}
