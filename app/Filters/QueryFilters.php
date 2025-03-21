<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilters
{
    protected Request $request;
    protected Builder $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name) || empty($value)) {
                continue;
            }
            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->builder;
    }

    public function filters(): array
    {
        return $this->request->all();
    }
}
