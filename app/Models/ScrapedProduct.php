<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScrapedProduct extends Model
{
    use Filterable;

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

    public function averageRating(): float
    {
        $rating = $this->rating;
        $totalVotes = array_sum($rating);
        $totalScore = 0;
        foreach ($rating as $stars => $votes) {
            $totalScore += $stars * $votes;
        }

        return $totalVotes > 0 ? round($totalScore / $totalVotes, 2) : 0;
    }
}
