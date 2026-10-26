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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('SolarTech Services');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('company_intro')->nullable();
            $table->string('cta_title')->nullable();
            $table->text('cta_text')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->string('cta_button_link')->nullable();
            $table->string('about_title')->nullable();
            $table->longText('about_content')->nullable();
            $table->longText('mission')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('history')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('google_map_embed')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
