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
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('subject')->nullable()->after('phone');
            $table->boolean('is_connected')->default(false)->after('read_at');
            $table->timestamp('connected_at')->nullable()->after('is_connected');
            $table->text('admin_notes')->nullable()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['phone', 'subject', 'is_connected', 'connected_at', 'admin_notes']);
        });
    }
};
