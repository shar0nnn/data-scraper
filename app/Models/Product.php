<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    const string IMAGES_PATH = Image::ROOT_PATH . 'products';
    protected $fillable = [
        'title',
        'description',
        'manufacturer_part_number',
        'pack_size_id',
    ];

    public function packSize(): BelongsTo
    {
        return $this->belongsTo(PackSize::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function retailers(): BelongsToMany
    {
        return $this->belongsToMany(Retailer::class)->withPivot('url')->withTimestamps();
    }

    public function scrapedProducts(): HasMany
    {
        return $this->hasMany(ScrapedProduct::class);
    }
}
