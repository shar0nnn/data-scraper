<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;

class QueryStringFilters
{
    protected EloquentBuilder $eloquentBuilder;
    protected QueryBuilder $queryBuilder;

    public array $appliedFilters = [];

    public function __construct(protected Request $request)
    {
    }

    public function apply(QueryBuilder|EloquentBuilder $builder): EloquentBuilder|QueryBuilder
    {
        if ($builder instanceof EloquentBuilder) {
            $this->setEloquentBuilder($builder);
        }

        if ($builder instanceof QueryBuilder) {
            $this->setQueryBuilder($builder);
        }

        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name) || empty($value)) {
                continue;
            }

            $this->$name($value);
            $this->appliedFilters[$name] = $value;
        }

        return $this->eloquentBuilder ?? $this->queryBuilder;
    }

    private function filters(): array
    {
        return $this->request->all();
    }

    public function setEloquentBuilder(EloquentBuilder $builder): void
    {
        $this->eloquentBuilder = $builder;
    }

    public function setQueryBuilder(QueryBuilder $builder): void
    {
        $this->queryBuilder = $builder;
    }
}
