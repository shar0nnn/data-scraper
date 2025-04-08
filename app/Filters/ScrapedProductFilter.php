<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ScrapedProductFilter extends QueryFilters
{
    protected bool $joinedScrapingSessions = false;

    public function __construct(protected Request $request)
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
        $this->joinScrapingSessionsIfNeeded();
        $this->queryBuilder->whereDate('scraping_sessions.created_at', '>=', $value);
    }

    public function end_date($value): void
    {
        $this->joinScrapingSessionsIfNeeded();
        $this->queryBuilder->whereDate('scraping_sessions.created_at', '<=', $value);
    }

    protected function joinScrapingSessionsIfNeeded(): void
    {
        if (!$this->joinedScrapingSessions) {
            $this->queryBuilder->join('scraping_sessions', 'scraping_session_id', '=', 'scraping_sessions.id');
            $this->joinedScrapingSessions = true;
        }
    }
}
