<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Setting
 */
class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'site_name' => $this->site_name,
            'logo_path' => $this->logo_path,
            'favicon_path' => $this->favicon_path,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'accent_color' => $this->accent_color,
            'text_color' => $this->text_color,
            'bg_color' => $this->bg_color,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'seo_keywords' => $this->seo_keywords,
            'hero_title' => $this->hero_title,
            'hero_subtitle' => $this->hero_subtitle,
            'hero_slider_images' => $this->hero_slider_images,
            'company_intro' => $this->company_intro,
            'cta_title' => $this->cta_title,
            'cta_text' => $this->cta_text,
            'cta_button_text' => $this->cta_button_text,
            'cta_button_link' => $this->cta_button_link,
            'about_title' => $this->about_title,
            'about_content' => $this->about_content,
            'mission' => $this->mission,
            'vision' => $this->vision,
            'history' => $this->history,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'contact_address' => $this->contact_address,
            'google_map_embed' => $this->google_map_embed,
            'social_links' => $this->social_links,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
