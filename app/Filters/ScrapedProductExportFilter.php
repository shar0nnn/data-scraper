<?php

namespace App\Filters;

use App\Actions\ParseToDateString;
use Illuminate\Http\Request;

class ScrapedProductExportFilter extends QueryStringFilters
{
    public function __construct(
        protected Request         $request,
        private ParseToDateString $parseToDateString
    )
    {
        parent::__construct($request);
    }

    public function start_date($value): void
    {
        $this->eloquentBuilder->whereHas('scrapingSession', function ($query) use ($value) {
            $query->whereDate('created_at', '>=', $this->parseToDateString->execute($value));
        });
    }

    public function end_date($value): void
    {
        $this->eloquentBuilder->whereHas('scrapingSession', function ($query) use ($value) {
            $query->whereDate('created_at', '<=', $this->parseToDateString->execute($value));
        });
    }
}
