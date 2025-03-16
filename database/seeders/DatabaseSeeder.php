<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Image;
use App\Models\PackSize;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\ScrapedImage;
use App\Models\ScrapedProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        DB::statement('SET FOREIGN_KEY_CHECKS=0');
//        ScrapedImage::query()->truncate();
//        Image::query()->truncate();
//        ScrapedProduct::query()->truncate();
//        Product::query()->truncate();
//        Retailer::query()->truncate();
//        PackSize::query()->truncate();
//        Currency::query()->truncate();
//        DB::statement('SET FOREIGN_KEY_CHECKS=1');

//        ScrapedImage::query()->delete();
//        Image::query()->delete();
//        ScrapedProduct::query()->delete();
//        Product::query()->delete();
//        Retailer::query()->delete();
//        PackSize::query()->delete();
//        Currency::query()->delete();

        $this->call([
            PackSizeSeeder::class,
            CurrencySeeder::class,
            RetailerSeeder::class,
            ProductSeeder::class,
            ScrapedProductSeeder::class,
        ]);
    }
}
