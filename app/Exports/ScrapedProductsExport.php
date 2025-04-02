<?php

namespace App\Exports;

use App\Models\ScrapedProduct;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ScrapedProductsExport implements FromCollection
{
    public function collection(): Collection|\Illuminate\Support\Collection
    {
        return ScrapedProduct::all();
    }
}
