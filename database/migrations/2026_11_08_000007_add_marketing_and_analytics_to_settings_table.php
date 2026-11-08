<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('meta_pixel_id')->nullable()->after('google_map_embed');
            $table->text('meta_capi_token')->nullable()->after('meta_pixel_id');
            $table->string('ga4_measurement_id')->nullable()->after('meta_capi_token');
            $table->string('whatsapp_tracking_number')->nullable()->after('ga4_measurement_id');
            $table->boolean('enable_marketing_tracking')->default(true)->after('whatsapp_tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'meta_pixel_id',
                'meta_capi_token',
                'ga4_measurement_id',
                'whatsapp_tracking_number',
                'enable_marketing_tracking',
            ]);
        });
    }
};

