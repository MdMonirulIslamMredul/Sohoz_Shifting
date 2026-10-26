<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'bg_color',
        'meta_title',
        'meta_description',
        'seo_keywords',
        'hero_title',
        'hero_subtitle',
        'hero_slider_images',
        'company_intro',
        'cta_title',
        'cta_text',
        'cta_button_text',
        'cta_button_link',
        'about_title',
        'about_content',
        'mission',
        'vision',
        'history',
        'contact_email',
        'contact_phone',
        'contact_address',
        'google_map_embed',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
        'hero_slider_images' => 'array',
    ];
}
