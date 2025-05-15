<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Image;
use App\Models\Location;
use App\Models\PackSize;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\Role;
use App\Models\ScrapedImage;
use App\Models\ScrapedProduct;
use App\Models\ScrapingSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('userables')->delete();
        DB::table('personal_access_tokens')->delete();
        ScrapedImage::query()->delete();
        Image::query()->delete();
        ScrapedProduct::query()->delete();
        ScrapingSession::query()->delete();
        DB::table('product_retailer')->delete();
        Product::query()->delete();
        Retailer::query()->delete();
        PackSize::query()->delete();
        Currency::query()->delete();
        User::query()->delete();
        Location::query()->delete();
        Role::query()->delete();

        $this->call([
            LocationSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PackSizeSeeder::class,
            CurrencySeeder::class,
            RetailerSeeder::class,
            ProductSeeder::class,
            ProductRetailerSeeder::class,
            ScrapingSessionSeeder::class,
            ScrapedProductSeeder::class,
            ScrapedImageSeeder::class,
        ]);
    }
}
