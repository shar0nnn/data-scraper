<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapingSession extends Model
{
    protected $fillable = [
        'session_key',
        'status',
    ];
}
