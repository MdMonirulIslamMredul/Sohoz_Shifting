<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Setting>
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_name' => fake()->name(),
            'logo_path' => fake()->word(),
            'favicon_path' => fake()->word(),
            'primary_color' => fake()->hexColor(),
            'secondary_color' => fake()->hexColor(),
            'accent_color' => fake()->hexColor(),
            'text_color' => fake()->paragraph(),
            'bg_color' => fake()->hexColor(),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'seo_keywords' => fake()->word(),
            'hero_title' => fake()->sentence(),
            'hero_subtitle' => fake()->sentence(),
            'hero_slider_images' => fake()->imageUrl(),
            'company_intro' => fake()->word(),
            'cta_title' => fake()->sentence(),
            'cta_text' => fake()->paragraph(),
            'cta_button_text' => fake()->paragraph(),
            'cta_button_link' => fake()->url(),
            'about_title' => fake()->sentence(),
            'about_content' => fake()->paragraph(),
            'mission' => fake()->word(),
            'vision' => fake()->word(),
            'history' => fake()->word(),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
            'contact_address' => fake()->address(),
            'google_map_embed' => fake()->word(),
            'social_links' => fake()->url(),
        ];
    }
}
