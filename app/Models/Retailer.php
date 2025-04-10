<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function scrapedProducts(): HasMany
    {
        return $this->hasMany(ScrapedProduct::class);
    }
}
