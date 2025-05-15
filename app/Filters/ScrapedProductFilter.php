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
        $this->queryBuilder
            ->joinOnce('product_retailer', 'scraped_products.product_retailer_id', '=', 'product_retailer.id')
            ->joinOnce('retailers', 'retailers.id', '=', 'product_retailer.retailer_id')
            ->whereIn('retailers.id', SplitString::handle($values));
    }

    public function product_ids($values): void
    {
        $this->queryBuilder
            ->joinOnce('product_retailer', 'scraped_products.product_retailer_id', '=', 'product_retailer.id')
            ->joinOnce('products', 'products.id', '=', 'product_retailer.product_id')
            ->whereIn('products.id', SplitString::handle($values));
    }

    public function manufacturer_part_numbers($values): void
    {
        $this->queryBuilder
            ->joinOnce('product_retailer', 'scraped_products.product_retailer_id', '=', 'product_retailer.id')
            ->joinOnce('products', 'products.id', '=', 'product_retailer.product_id')
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
