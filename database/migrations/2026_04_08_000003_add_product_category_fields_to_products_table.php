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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('product_category_id')->nullable()->after('price')->constrained('product_categories')->nullOnDelete();
            $table->foreignId('product_subcategory_id')->nullable()->after('product_category_id')->constrained('product_subcategories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_subcategory_id']);
            $table->dropForeign(['product_category_id']);
            $table->dropColumn(['product_subcategory_id', 'product_category_id']);
        });
    }
};
