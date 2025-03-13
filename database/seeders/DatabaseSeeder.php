<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PackSizeSeeder::class,
            ProductSeeder::class,
            CurrencySeeder::class,
            RetailerSeeder::class,
            ProductRatingSeeder::class,
            ScrapedProductSeeder::class,
            ImageSeeder::class,
        ]);
    }
}
