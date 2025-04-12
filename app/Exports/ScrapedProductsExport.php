<?php

namespace App\Exports;

use App\Filters\ScrapedProductExportFilter;
use App\Models\ScrapedProduct;
use App\Services\DateService;
use Generator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScrapedProductsExport extends SpreadsheetExport implements FromGenerator, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(private ScrapedProductExportFilter $filter)
    {
        parent::__construct();
        $this->fileName = 'scraped-products-' . Str::random() . '.xlsx';
    }

    public function headings(): array
    {
        return [
            'id',
            'product',
            'retailer',
            'price',
            'stock_count',
            'rating',
            'scraped_at'
        ];
    }

    public function map($scrapedProduct): array
    {
        $this->rowNumber++;

        return [
            $scrapedProduct->id,
            $scrapedProduct->product->title,
            $scrapedProduct->retailer->title,
            $scrapedProduct->price,
            $scrapedProduct->stock_count,
            $scrapedProduct->rating,
            $scrapedProduct->scrapingSession->created_at
                ? DateService::toDMY($scrapedProduct->scrapingSession->created_at)
                : null
        ];
    }

    public function generator(): Generator
    {
        foreach (
            $this->filter->apply(
                ScrapedProduct::query()
                    ->with(['product:id,title', 'retailer:id,title', 'scrapingSession:id,created_at'])
                    ->select(['id', 'product_id', 'retailer_id', 'scraping_session_id', 'price', 'stock_count', 'rating'])
                    ->orderBy('id')
            )->lazy(5000) as $scrapedProduct
        ) {
            yield $scrapedProduct;
        }
    }
}
