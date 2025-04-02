<?php

namespace App\Exports;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
//    use Exportable;
    public function collection(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::all());
    }

//    public function sheets(): array
//    {
//        $sheets = [];
//        for ($i = 1; $i <= 3; $i++) {
//            $sheets[] = new ProductSheet($i);
//        }
//
//        return $sheets;
//    }
}
