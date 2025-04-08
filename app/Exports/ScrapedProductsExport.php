<?php

namespace App\Exports;

use App\Filters\ScrapedProductExportFilter;
use App\Models\ScrapedProduct;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScrapedProductsExport extends SpreadsheetExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(private ScrapedProductExportFilter $filter)
    {
        parent::__construct();
        $this->fileName = 'scraped-products-' . Str::random() . '.xlsx';
    }

    public
    function headings(): array
    {
        return [
            'id',
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
        $this->rowNumber++;

        return [
            $scrapedProduct->id,
            $scrapedProduct->product->title,
            $scrapedProduct->retailer->title,
            $scrapedProduct->price,
            $scrapedProduct->stock_count,
            $scrapedProduct->rating,
        ];
    }

    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return $this->filter->apply(
            ScrapedProduct::query()
                ->with(['product:id,title', 'retailer:id,title'])
                ->select(['id', 'product_id', 'retailer_id', 'price', 'stock_count', 'rating'])
                ->orderBy('id')
        );
    }
}
