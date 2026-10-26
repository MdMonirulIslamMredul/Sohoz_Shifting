<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('primary_color')->default('#0d6efd')->after('favicon_path');
            $table->string('secondary_color')->default('#6c757d')->after('primary_color');
            $table->string('accent_color')->default('#f39c12')->after('secondary_color');
            $table->string('text_color')->default('#333333')->after('accent_color');
            $table->string('bg_color')->default('#ffffff')->after('text_color');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'primary_color',
                'secondary_color',
                'accent_color',
                'text_color',
                'bg_color',
            ]);
        });
    }
};
