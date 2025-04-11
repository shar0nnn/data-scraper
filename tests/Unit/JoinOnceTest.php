<?php

namespace Unit;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class JoinOnceTest extends TestCase
{
    public function test_join_once_macro_ensures_query_builder_joins_same_table_only_once()
    {
        $builder = DB::table('scraped_products');
        $builder->joinOnce('products', 'scraped_products.product_id', '=', 'products.id');
        $builder->joinOnce('products', 'scraped_products.product_id', '=', 'products.id');
        $builder->joinOnce('retailers', 'scraped_products.retailer_id', '=', 'retailers.id');
        $builder->joinOnce('retailers', 'scraped_products.retailer_id', '=', 'retailers.id');
        $this->assertCount(2, $builder->joins);
    }
}
