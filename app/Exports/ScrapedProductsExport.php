<?php

namespace App\Exports;

use App\Filters\ScrapedProductExportFilter;
use App\Models\ScrapedProduct;
use App\Traits\HasExportStats;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScrapedProductsExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use RegistersEventListeners, Exportable, HasExportStats;

    public string $fileName;
    private int $number = 1;

    public function __construct(private ScrapedProductExportFilter $filter)
    {
        $this->fileName = 'scraped-products-' . Str::random() . '.xlsx';
    }

    public
    function headings(): array
    {
        return [
            'number',
            'product',
            'retailer',
            'price',
            'stock_count',
            'rating',
        ];
    }

    public
    function map($scrapedProduct): array
    {
        $this->fileRows++;

        return [
            $this->number++,
            $scrapedProduct->product->title,
            $scrapedProduct->retailer->title,
            $scrapedProduct->price,
            $scrapedProduct->stock_count,
            $scrapedProduct->rating,
        ];
    }

    public
    function query(): Relation|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return $this->filter->apply(ScrapedProduct::with(['product', 'retailer']));
    }
}
