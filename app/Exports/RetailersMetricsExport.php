<?php

namespace App\Exports;

use App\Filters\ScrapedProductFilter;
use App\Services\RetailerService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RetailersMetricsExport extends SpreadsheetExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        private RetailerService      $retailerService,
        private ScrapedProductFilter $filter
    )
    {
        parent::__construct();
        $this->fileName = 'retailers-metrics-' . Str::random() . '.xlsx';
    }

    public function headings(): array
    {
        return [
            'retailer_id',
            'retailer_title',
            'average_price',
            'average_rating',
            'average_number_of_images',
            'start_date',
            'end_date',
        ];
    }

    public function map($scrapedProduct): array
    {
        $this->rowNumber++;

        return [
            $scrapedProduct->retailer_id,
            $scrapedProduct->retailer_title,
            $scrapedProduct->average_price,
            $scrapedProduct->average_rating,
            $scrapedProduct->average_number_of_images,
            $this->filter->appliedFilters['start_date'] ?? null,
            $this->filter->appliedFilters['end_date'] ?? null,
        ];
    }

    public function query(): Builder
    {
        return $this->retailerService->metrics($this->filter)->orderBy('retailer_id');
    }
}
