<?php

namespace Unit;

use App\Filters\ScrapedProductFilter;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class QueryStringFiltersTest extends TestCase
{
    public function test_apply_method_applies_valid_filters()
    {
        $request = Mockery::mock(Request::class);
        $builder = Mockery::mock(Builder::class);
        $validFilters = [
            'retailer_ids' => '1,2,3',
            'product_ids' => '4,5,6',
        ];

        $request->shouldReceive('all')->andReturn($validFilters);

        $filter = new ScrapedProductFilter($request);

        $builder->shouldReceive('whereIn')->times(2);

        $filter->apply($builder);
    }

    public function test_apply_method_does_not_apply_invalid_filters()
    {
        $spy = Mockery::spy(ScrapedProductFilter::class);
        $request = Mockery::mock(Request::class);
        $builder = Mockery::mock(Builder::class);
        $invalidFilters = [
            'retailer_ids' => '',
        ];

        $request->shouldReceive('all')->andReturn($invalidFilters);
        $filter = new ScrapedProductFilter($request);

        $spy->shouldNotReceive('retailer_ids');

        $filter->apply($builder);
    }
}
