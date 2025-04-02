<?php

namespace App\Exports;

use App\Filters\ScrapedProductFilter;
use App\Services\RetailerService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class RetailersMetricsExport implements FromCollection
{
    public function __construct(
        protected ScrapedProductFilter $scrapedProductFilter,
    )
    {
    }

    public function collection(): Collection
    {
        return new RetailerService()->metrics($this->scrapedProductFilter);
    }
}
