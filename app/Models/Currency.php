<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'description',
        'symbol',
    ];

    public function retailers(): HasMany
    {
        return $this->hasMany(Retailer::class);
    }
}
