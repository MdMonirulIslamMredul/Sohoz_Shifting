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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('page_details')->nullable();
            $table->string('banner_image')->nullable();
            $table->longText('details1')->nullable();
            $table->string('image1')->nullable();
            $table->longText('details2')->nullable();
            $table->string('image2')->nullable();
            $table->longText('details3')->nullable();
            $table->longText('details4')->nullable();
            $table->json('key_values')->nullable();
            $table->unsignedInteger('years_experience')->nullable();
            $table->unsignedSmallInteger('establishment_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
