<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ScrapedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_retailer_id',
        'price',
        'stock_count',
        'rating',
        'scraping_session_id',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'array',
        ];
    }

    public function productRetailer(): BelongsTo
    {
        return $this->belongsTo(ProductRetailer::class);
    }

    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(
            Product::class,
            ProductRetailer::class,
            'id',
            'id',
            'product_retailer_id',
            'product_id'
        );
    }

    public function retailer(): HasOneThrough
    {
        return $this->hasOneThrough(
            Retailer::class,
            ProductRetailer::class,
            'id',
            'id',
            'product_retailer_id',
            'retailer_id'
        );
    }

    public function scrapingSession(): BelongsTo
    {
        return $this->belongsTo(ScrapingSession::class);
    }
}
