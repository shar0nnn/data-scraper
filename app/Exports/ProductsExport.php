<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport extends SpreadsheetExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct()
    {
        parent::__construct();
        $this->fileName = 'products-' . Str::random() . '.xlsx';
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'manufacturer_part_number',
            'pack_size'
        ];
    }

    public function map($product): array
    {
        $this->rowNumber++;

        return [
            $product->id,
            $product->title,
            $product->description,
            $product->manufacturer_part_number,
            $product->pack_size,
        ];
    }

    public function query(): Builder
    {
        return DB::table('products')
            ->select('products.id as id', 'title', 'description', 'manufacturer_part_number', 'pack_sizes.name as pack_size')
            ->join('pack_sizes', 'products.pack_size_id', '=', 'pack_sizes.id')
            ->orderBy('id');
    }
}
