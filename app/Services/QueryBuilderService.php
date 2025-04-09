<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;

class QueryBuilderService
{
    public function joinOnce(
        Builder $builder,
        string  $table,
        string  $first,
        string  $operator,
        string  $second,
        string  $type = 'inner'
    ): Builder
    {
        if ($this->builderHasJoin($builder, $table)) {
            return $builder;
        }

        return $builder->join($table, $first, $operator, $second, $type);
    }

    private function builderHasJoin(Builder $builder, string $table): bool
    {
        return array_any($builder->joins, fn($join) => $join->table === $table);
    }
}
