<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Retailer extends Model
{
    use HasFactory;

    const string LOGO_PATH = Image::ROOT_PATH . 'retailers';
    protected $fillable = [
        'title',
        'url',
        'currency_id',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('url');
    }

    public function scrapedProducts(): HasManyThrough
    {
        return $this->hasManyThrough(
            ScrapedProduct::class,
            ProductRetailer::class,
            'retailer_id',
            'product_retailer_id',
            'id',
            'id'
        );
    }

    public function scrapedImages(): HasManyThrough
    {
        return $this->hasManyThrough(
            ScrapedImage::class,
            ProductRetailer::class,
            'retailer_id',
            'product_retailer_id',
            'id',
            'id'
        );
    }
}
