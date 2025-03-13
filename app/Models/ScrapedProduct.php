<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedProduct extends Model
{
    protected $fillable = [
        'product_id',
        'retailer_id',
        'price',
        'stock_count',
        'scraped_at',
    ];
}
