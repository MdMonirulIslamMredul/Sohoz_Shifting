<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Blog;
use App\Models\Gallery;
use App\Models\HomepageCarousel;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminUser = User::updateOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin User',
            'password' => '12345678',
            'is_admin' => true,
        ]);

        $permissions = [
            'access admin panel',
            'view',
            'create',
            'edit',
            'delete',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage products',
            'manage services',
            'manage team members',
            'manage blogs',
            'manage galleries',
            'manage contacts',
            'Web_Settings',
            'manage branches',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $branchAdminRole = Role::firstOrCreate(['name' => 'Branch Admin']);
        $productAdminRole = Role::firstOrCreate(['name' => 'Product Admin']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $superAdminRole->syncPermissions($permissions);
        $adminRole->syncPermissions([
            'access admin panel',
            'view',
            'create',
            'edit',
            'delete',
            'manage users',
            'manage roles',
            'manage permissions',
            'manage products',
            'manage services',
            'manage team members',
            'manage blogs',
            'manage galleries',
            'manage contacts',
            'Web_Settings',
        ]);
        $branchAdminRole->syncPermissions(['access admin panel', 'view', 'create', 'edit', 'manage branches']);
        $productAdminRole->syncPermissions(['access admin panel', 'view', 'create', 'edit', 'manage products']);
        $editorRole->syncPermissions(['access admin panel', 'view', 'create', 'edit']);
        $userRole->syncPermissions(['view']);

        $adminUser->assignRole($superAdminRole);

        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'SolarTech Services',
            'meta_title' => 'SolarTech Services - Renewable Energy Solutions',
            'meta_description' => 'SolarTech Services offers reliable solar installation, maintenance, and renewable solutions for homes and businesses.',
            'seo_keywords' => 'solar, renewable energy, solar service, solar panel',
            'hero_title' => 'Power Your Future with Solar Energy',
            'hero_subtitle' => 'Smart, efficient, and affordable solar systems for modern homes and industries.',
            'company_intro' => 'We help clients reduce electricity costs and carbon impact through complete solar engineering and service support.',
            'cta_title' => 'Start Saving Energy Today',
            'cta_text' => 'Request a free solar consultation from our experts and get a custom solution.',
            'cta_button_text' => 'Book Free Consultation',
            'cta_button_link' => '/contact',
            'about_title' => 'About SolarTech Services',
            'about_content' => 'SolarTech Services is a trusted renewable energy company focused on quality installations and long-term customer success.',
            'mission' => 'Deliver affordable and sustainable energy solutions with honest service.',
            'vision' => 'Become the leading clean energy partner in the region.',
            'history' => 'Started as a local engineering team, we have grown into a full-service solar solutions provider.',
            'contact_email' => 'info@solartechservices.com',
            'contact_phone' => '+880 1700-000000',
            'contact_address' => 'House 12, Road 5, Dhaka, Bangladesh',
            'google_map_embed' => 'https://maps.google.com/maps?q=Dhaka&t=&z=13&ie=UTF8&iwloc=&output=embed',
            'social_links' => [
                'facebook' => 'https://facebook.com',
                'linkedin' => 'https://linkedin.com',
                'youtube' => 'https://youtube.com',
            ],
        ]);

        About::updateOrCreate(['id' => 1], [
            'title' => 'About SolarTech Services',
            'page_details' => '<p>We design and deliver reliable renewable energy systems for homes and businesses with a focus on quality, safety, and long-term value.</p>',
            'details1' => '<p>Our team handles complete project execution from site survey and design to installation and maintenance.</p>',
            'details2' => '<p>We use quality components and engineering-backed planning to maximize energy yield and system life.</p>',
            'details3' => '<p>Every client receives tailored recommendations based on power consumption, roof structure, and budget.</p>',
            'details4' => '<p>Our support team stays connected after deployment to ensure smooth operations and faster issue resolution.</p>',
            'key_values' => ['Craftsmanship', 'Innovation', 'Sustainability', 'Customer-Centric'],
            'years_experience' => 12,
            'establishment_year' => 2014,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 1], [
            'title' => 'Future-Ready Solar Energy Solutions',
            'subtitle' => 'We design, install, and maintain high-performance solar systems for homes and businesses.',
            'is_active' => true,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 2], [
            'title' => 'Smart Solar Installations For Every Roof',
            'subtitle' => 'From residential rooftops to large commercial sites, we deliver engineered systems built to last.',
            'is_active' => true,
        ]);

        HomepageCarousel::updateOrCreate(['sort_order' => 3], [
            'title' => 'Lower Bills, Cleaner Future',
            'subtitle' => 'Switch to reliable renewable power and reduce electricity costs with full support from our experts.',
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 8; $i++) {
            Product::updateOrCreate(['slug' => 'solar-product-' . $i], [
                'title' => 'Solar Product ' . $i,
                'short_description' => 'High-efficiency solar product ' . $i . ' for reliable performance.',
                'description' => 'Detailed information about Solar Product ' . $i . '. This item is part of the reusable Laravel template demo data.',
                'price' => 1200 + ($i * 45),
                'is_featured' => $i <= 4,
                'status' => true,
                'sort_order' => $i,
            ]);

            // Shifting services are handled by SohozShiftingSiteOverhaulSeeder
        }

        for ($i = 1; $i <= 5; $i++) {
            Blog::updateOrCreate(['slug' => 'solar-blog-' . $i], [
                'title' => 'Solar Blog Post ' . $i,
                'excerpt' => 'Key insights from solar blog post ' . $i . '.',
                'body' => '<p>This is demo content for Solar Blog Post ' . $i . '. You can edit this from the admin panel using CKEditor.</p>',
                'meta_title' => 'Solar Blog Post ' . $i,
                'meta_description' => 'SEO description for solar blog post ' . $i,
                'seo_keywords' => 'solar, blog, renewable',
                'published_at' => now()->subDays($i),
                'is_published' => true,
            ]);

            TeamMember::updateOrCreate(['name' => 'Team Member ' . $i], [
                'designation' => 'Solar Engineer ' . $i,
                'bio' => 'Experienced in designing and deploying scalable solar systems.',
                'facebook_url' => 'https://facebook.com',
                'linkedin_url' => 'https://linkedin.com',
                'twitter_url' => 'https://twitter.com',
                'sort_order' => $i,
                'status' => true,
            ]);
        }

        for ($i = 1; $i <= 6; $i++) {
            Gallery::updateOrCreate(['title' => 'Project Photo ' . $i], [
                'type' => 'photo',
                'description' => 'Gallery photo item ' . $i,
                'status' => true,
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            Gallery::updateOrCreate(['title' => 'Project Video ' . $i], [
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Gallery video item ' . $i,
                'status' => true,
            ]);
        }

        // Call Sohoz Shifting seeders
        $this->call([
            PropertyTypeSeeder::class,
            SohozShiftingSiteOverhaulSeeder::class,
            SohozShiftingFaqSeeder::class,
        ]);
    }
}
