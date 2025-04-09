<?php

namespace App\Filters;

use App\Actions\ParseToDateString;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;

class ScrapedProductFilter extends QueryStringFilters
{
    public function __construct(
        protected Request           $request,
        private QueryBuilderService $queryBuilderService,
        private ParseToDateString   $parseToDateString,
    )
    {
        parent::__construct($request);
    }

    private function explodeValues(string $values): array
    {
        return array_filter(explode(',', $values));
    }

    public function retailer_ids($value): void
    {
        $this->queryBuilder->whereIn('retailer_id', $this->explodeValues($value));
    }

    public function product_ids($value): void
    {
        $this->queryBuilder->whereIn('product_id', $this->explodeValues($value));
    }

    public function manufacturer_part_numbers($value): void
    {
        $this->queryBuilder
            ->join('products', 'product_id', '=', 'products.id')
            ->whereIn('manufacturer_part_number', $this->explodeValues($value));
    }

    public function start_date($value): void
    {
        $this->queryBuilderService
            ->joinOnce($this->queryBuilder, 'scraping_sessions', 'scraping_session_id', '=', 'scraping_sessions.id')
            ->whereDate('scraping_sessions.created_at', '>=', $this->parseToDateString->execute($value));
    }

    public function end_date($value): void
    {
        $this->queryBuilderService
            ->joinOnce($this->queryBuilder, 'scraping_sessions', 'scraping_session_id', '=', 'scraping_sessions.id')
            ->whereDate('scraping_sessions.created_at', '<=', $this->parseToDateString->execute($value));
    }
}
