<?php

namespace App\Providers;

use App\Policies\AdminPolicy;
use App\Policies\UserPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->configureDates();

        // Query builder adds a join only if the table hasn't already been joined
        Builder::macro('joinOnce', function ($table, $first, $operator, $second, $type = 'inner') {
            if (collect($this->joins)->pluck('table')->contains($table)) {
                return $this;
            }

            return $this->join($table, $first, $operator, $second, $type);
        });
    }

    public function boot(): void
    {
        Gate::define('admin-crud', [AdminPolicy::class, 'crud']);
        Gate::define('user-crud', [UserPolicy::class, 'crud']);
    }

    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }
}
