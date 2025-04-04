<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ScrapedProductExportFilter extends QueryFilters
{
    public function __construct(protected Request $request)
    {
        parent::__construct($request);
    }

    public function start_date($value): void
    {
        $this->eloquentBuilder->whereHas('scrapingSession', function ($query) use ($value) {
            $query->whereDate('created_at', '>=', Carbon::parse($value)->toDateString());
        });
    }

    public function end_date($value): void
    {
        $this->eloquentBuilder->whereHas('scrapingSession', function ($query) use ($value) {
            $query->whereDate('created_at', '<=', Carbon::parse($value)->toDateString());
        });
    }
}
