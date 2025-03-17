<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->dropIndex('scraped_products_session_key_index');
            $table->dropColumn('session_key');
            $table->foreignId('scraping_session_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scraped_products', function (Blueprint $table) {
            $table->dropForeign('scraped_products_scraping_session_id_foreign');
            $table->dropColumn('scraping_session_id');
            $table->char('session_key', 16)->index();
        });
    }
};
