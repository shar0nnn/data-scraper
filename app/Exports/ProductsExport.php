<?php

namespace App\Exports;

use App\Models\Product;
use App\Traits\HasExportStats;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    use RegistersEventListeners, Exportable, HasExportStats;

    public string $fileName = 'products.xlsx';
    protected int $number = 1;

    public function headings(): array
    {
        return [
            'number',
            'title',
            'description',
            'manufacturer_part_number',
            'pack_size'
        ];
    }

    public function map($product): array
    {
        $this->fileRows++;

        return [
            $this->number++,
            $product->title,
            $product->description,
            $product->manufacturer_part_number,
            $product->packSize->name,
        ];
    }

    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
    {
        return Product::query();
    }
}
