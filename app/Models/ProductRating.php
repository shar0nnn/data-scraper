<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $fillable = [
        'product_id',
        'retailer_id',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'array',
        ];
    }
}
