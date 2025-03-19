<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    const string ROOT_PATH = 'images/';
    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'link',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
