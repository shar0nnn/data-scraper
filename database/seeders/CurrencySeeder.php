<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'USD', 'description' => 'American dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'description' => 'Euro', 'symbol' => '€'],
            ['code' => 'UAH', 'description' => 'Ukrainian hryvnia', 'symbol' => '₴'],
            ['code' => 'GBP', 'description' => 'British pound sterling', 'symbol' => '£'],
            ['code' => 'JPY', 'description' => 'Japanese yen', 'symbol' => '¥'],
            ['code' => 'CAD', 'description' => 'Canadian dollar', 'symbol' => 'C$'],
            ['code' => 'AUD', 'description' => 'Australian dollar', 'symbol' => 'A$'],
            ['code' => 'INR', 'description' => 'Indian rupee', 'symbol' => '₹'],
            ['code' => 'KRW', 'description' => 'South Korean won', 'symbol' => '₩'],
        ];

        foreach ($currencies as $currency) {
            Currency::query()->create($currency);
        }
    }
}
