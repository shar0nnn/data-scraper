<?php

namespace App\Filters;

use App\Services\DateService;
use Illuminate\Http\Request;

class ScrapedProductExportFilter extends QueryStringFilters
{
    public function __construct(protected Request $request)
    {
        parent::__construct($request);
    }

    public function start_date($value): void
    {
        $date = DateService::toDateString($value);

        if ($date) {
            $this->eloquentBuilder->whereHas('scrapingSession', function ($query) use ($date) {
                $query->whereDate('created_at', '>=', $date);
            });
        }
    }

    public function end_date($value): void
    {
        $date = DateService::toDateString($value);

        if ($date) {
            $this->eloquentBuilder->whereHas('scrapingSession', function ($query) use ($date) {
                $query->whereDate('created_at', '<=', $date);
            });
        }
    }
}
