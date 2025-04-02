<?php

namespace App\Exports\Sheets;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductSheet implements FromCollection, WithTitle
{
    private $i;

    public function __construct(int $i)
    {
        $this->i = $i;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Month ' . $this->i;
    }

    public function collection()
    {
        return ProductResource::collection(Product::all());
    }
}
