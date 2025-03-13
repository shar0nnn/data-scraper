<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackSize extends Model
{
    /**
     * Indicates that the model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $fillable = ['name'];
}
