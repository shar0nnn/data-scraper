<?php

namespace App\Models;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $manufacturer_part_number
 * @property int $pack_size_id
 */
class Product extends Model
{
    use HasFactory, Filterable;

    const string IMAGES_PATH = Image::ROOT_PATH . 'products';

    protected $fillable = [
        'title',
        'description',
        'manufacturer_part_number',
        'pack_size_id',
    ];

    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'userable');
    }

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

    public function scrapedProducts(): HasManyThrough
    {
        return $this->hasManyThrough(
            ScrapedProduct::class,
            ProductRetailer::class,
            'product_id',
            'product_retailer_id',
            'id',
            'id'
        );
    }
}
