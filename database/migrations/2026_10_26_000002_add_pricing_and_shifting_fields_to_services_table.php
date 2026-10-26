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
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('description');
            $table->string('price_range')->nullable()->after('price');
            $table->string('pricing_type')->default('negotiable')->after('price_range'); // fixed, range, negotiable
            $table->boolean('show_price')->default(false)->after('pricing_type');
            $table->string('icon')->nullable()->default('bi-box-seam')->after('image');
            $table->boolean('is_addon_for_booking')->default(true)->after('show_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'price',
                'price_range',
                'pricing_type',
                'show_price',
                'icon',
                'is_addon_for_booking',
            ]);
        });
    }
};
