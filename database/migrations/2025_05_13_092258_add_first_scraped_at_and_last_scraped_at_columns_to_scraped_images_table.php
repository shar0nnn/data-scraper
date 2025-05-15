<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scraped_images', function (Blueprint $table) {
            $table->timestamp('first_scraped_at');
            $table->timestamp('last_scraped_at');
        });
    }
};
