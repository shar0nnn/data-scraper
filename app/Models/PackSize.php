<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackSize extends Model
{
    /**
     * Indicates that the model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $fillable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
