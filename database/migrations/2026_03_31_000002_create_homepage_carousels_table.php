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
        Schema::create('homepage_carousels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_carousels');
    }
};
