<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Query builder adds a join only if the table hasn't already been joined
        Builder::macro('joinOnce', function ($table, $first, $operator, $second, $type = 'inner') {
            if (collect($this->joins)->pluck('table')->contains($table)) {
                return $this;
            }

            return $this->join($table, $first, $operator, $second, $type);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
