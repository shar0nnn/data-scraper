<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }
}
