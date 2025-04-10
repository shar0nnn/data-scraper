<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapedImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'scraped_product_id',
        'url',
        'file_name'
    ];
}
