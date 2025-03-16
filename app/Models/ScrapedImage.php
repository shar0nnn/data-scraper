<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapedImage extends Model
{
    protected $fillable = [
        'scraped_product_id',
        'url',
    ];
}
