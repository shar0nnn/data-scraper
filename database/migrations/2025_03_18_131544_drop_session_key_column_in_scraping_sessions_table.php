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
        Schema::table('scraping_sessions', function (Blueprint $table) {
            $table->dropUnique('scraping_sessions_session_key_unique');
            $table->dropColumn('session_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scraping_sessions', function (Blueprint $table) {
            $table->char('session_key', 16)->unique();
        });
    }
};
