<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class QueryFilters
{
    protected Request $request;

    protected EloquentBuilder $eloquentBuilder;
    protected QueryBuilder $queryBuilder;

    public array $appliedFilters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(QueryBuilder|EloquentBuilder $builder): EloquentBuilder|QueryBuilder
    {
        if ($builder instanceof EloquentBuilder) {
            $this->eloquentBuilder = $builder;
        }

        if ($builder instanceof QueryBuilder) {
            $this->queryBuilder = $builder;
        }

        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name) || empty($value)) {
                continue;
            }

            $this->appliedFilters[$name] = $value;
            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->eloquentBuilder ?? $this->queryBuilder;
    }

    private function filters(): array
    {
        return $this->request->all();
    }
}
