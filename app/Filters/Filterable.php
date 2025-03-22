<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $builder, QueryFilters $queryFilters): Builder
    {
        return $queryFilters->apply($builder);
    }
}
