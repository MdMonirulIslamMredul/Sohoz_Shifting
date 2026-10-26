<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->longText('mission')->nullable()->after('key_values');
            $table->longText('vision')->nullable()->after('mission');
            $table->longText('history')->nullable()->after('vision');
        });

        // Copy existing mission, vision, history from settings table if present
        if (Schema::hasTable('settings')) {
            $setting = DB::table('settings')->first();
            if ($setting) {
                DB::table('abouts')->updateOrInsert(
                    ['id' => 1],
                    [
                        'mission' => $setting->mission ?? null,
                        'vision' => $setting->vision ?? null,
                        'history' => $setting->history ?? null,
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn(['mission', 'vision', 'history']);
        });
    }
};
