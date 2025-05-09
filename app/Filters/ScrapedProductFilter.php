<?php

namespace App\Filters;

use App\Actions\SplitString;
use App\Services\DateService;
use Illuminate\Http\Request;

class ScrapedProductFilter extends QueryStringFilters
{
    public function __construct(
        protected Request $request,
    )
    {
        parent::__construct($request);
    }


    public function retailer_ids($values): void
    {
        $this->queryBuilder->whereIn('retailer_id', SplitString::handle($values));
    }

    public function product_ids($values): void
    {
        $this->queryBuilder->whereIn('product_id', SplitString::handle($values));
    }

    public function manufacturer_part_numbers($values): void
    {
        $this->queryBuilder
            ->join('products', 'product_id', '=', 'products.id')
            ->whereIn('manufacturer_part_number', SplitString::handle($values));
    }

    public function start_date($value): void
    {
        $date = DateService::toDateString($value);

        if ($date) {
            $this->queryBuilder
                ->joinOnce('scraping_sessions', 'scraping_session_id', '=', 'scraping_sessions.id')
                ->whereDate('scraping_sessions.created_at', '>=', $date);
        }
    }

    public function end_date($value): void
    {
        $date = DateService::toDateString($value);

        if ($date) {
            $this->queryBuilder
                ->joinOnce('scraping_sessions', 'scraping_session_id', '=', 'scraping_sessions.id')
                ->whereDate('scraping_sessions.created_at', '<=', $date);
        }
    }
}
