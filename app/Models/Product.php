<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'retailer_id',
        'url',
        'title',
        'description',
        'manufacturer_part_number',
        'pack_size_id',
    ];
}
