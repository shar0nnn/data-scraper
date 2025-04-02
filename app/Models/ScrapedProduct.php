<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScrapedProduct extends Model
{
    protected $fillable = [
        'product_id',
        'retailer_id',
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scrapedImages(): HasMany
    {
        return $this->hasMany(ScrapedImage::class);
    }

    public function scrapingSession(): BelongsTo
    {
        return $this->belongsTo(ScrapingSession::class);
    }
}
