<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\Counter;
use App\Models\Faq;
use App\Models\HomepageCarousel;
use App\Models\PropertyType;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SohozShiftingSiteOverhaulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Settings Table Overhaul
        $setting = Setting::first() ?? new Setting();
        $setting->site_name = 'Sohoz Shifting';
        $setting->logo_path = 'settings/logo.png';
        $setting->meta_title = 'Sohoz Shifting | বাসা ও অফিস শিফটিং সার্ভিস | ঢাকা';
        $setting->meta_description = 'সহজ শিফটিং - ঢাকা ও সারাদেশে নিরাপদ ও সাশ্রয়ী বাসা এবং অফিস পরিবর্তনের বিশ্বস্ত প্ল্যাটফর্ম। দক্ষ লেবার, প্যাকিং ও পিকআপ ভ্যান বুকিং দিন।';
        $setting->seo_keywords = 'basa shifting, house shifting dhaka, office shifting, pickup van, covered van, packing service, sohoz shifting';
        $setting->hero_title = 'বাসা বদল হোক চাপমুক্ত, সহজে।';
        $setting->hero_subtitle = 'ঢাকা শহরে বাসা ও অফিস শিফটিং, দক্ষ লেবার, প্যাকিং ও পিকআপ ভ্যান। এক ক্লিকে বুকিং দিন।';
        $setting->primary_color = '#0f172a';
        $setting->accent_color = '#f59e0b';
        $setting->company_intro = 'সহজ শিফটিং ঢাকা শহরের একটি শীর্ষস্থানীয় ও নির্ভরযোগ্য বাসা এবং অফিস শিফটিং সেবা প্রদানকারী প্রতিষ্ঠান। আমরা দক্ষ টিম ও আধুনিক পরিবহনের মাধ্যমে সম্পূর্ণ নির্ঝঞ্ঝাট শিফটিং নিশ্চিত করি।';
        $setting->cta_title = 'বাসা বদলানোর তারিখ ঠিক করেছেন?';
        $setting->cta_text = 'আমাদের টিম প্রস্তুত রয়েছে আপনার মালামাল নিরাপদে নতুন ঠিকানায় পৌঁছে দেওয়ার জন্য। আজই ফ্রি কোটেশন নিন।';
        $setting->cta_button_text = 'বুকিং দিন';
        $setting->cta_button_link = '/booking';
        $setting->about_title = 'সহজ শিফটিং সম্পর্কে';
        $setting->about_content = 'সহজ শিফটিং ঢাকা শহর এবং সারাদেশ জুড়ে পেশাদার বাসা পরিবর্তন ও করপোরেট অফিস স্থানান্তরের বিশ্বস্ত প্রতিষ্ঠান। আমাদের রয়েছে অভিজ্ঞ লোডার, দক্ষ কার্পেন্টার, ইলেকট্রিশিয়ান, এসি টেকনিশিয়ান এবং নিজস্ব ওয়াটারপ্রুফ কাভার্ড ভ্যান বহর।';
        $setting->mission = 'গ্রাহকদের জন্য বাসা ও অফিস বদলানোর প্রক্রিয়াকে সর্বোচ্চ নিরাপদ, সময়োপযোগী এবং সাশ্রয়ী করে তোলা।';
        $setting->vision = 'বাংলাদেশের প্রতিটি পরিবার ও প্রতিষ্ঠানের কাছে সবচেয়ে নির্ভরযোগ্য এবং স্মার্ট শিফটিং পার্টনার হিসেবে প্রতিষ্ঠিত হওয়া।';
        $setting->history = '২০১৮ সালে যাত্রা শুরু করে সহজ শিফটিং আজ ঢাকার অন্যতম প্রধান শিফটিং সেবা প্রদানকারী প্ল্যাটফর্ম হিসেবে ৫,০০০+ পরিবার ও প্রতিষ্ঠানকে সফল সেবা প্রদান করেছে।';
        $setting->contact_email = 'info@sohozshifting.com';
        $setting->contact_phone = '01700-000000';
        $setting->contact_address = 'প্লট ১২, রোড ৪, ব্লক সি, মিরপুর ১০, ঢাকা ১২১৬';
        $setting->save();

        // 2. Homepage Hero Carousel Overhaul
        HomepageCarousel::truncate();
        HomepageCarousel::create([
            'title' => 'বাসা বদল হোক চাপমুক্ত, সহজে।',
            'subtitle' => 'ঢাকা শহরে বাসা ও অফিস শিফটিং, দক্ষ লেবার, প্যাকিং ও পিকআপ ভ্যান। এক ক্লিকে বুকিং দিন।',
            'image' => 'hero-slider/2diBzperYVoDGqrHmx98FE6m7fCsUgohw5iwCvAS.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        HomepageCarousel::create([
            'title' => 'অভিজ্ঞ লেবার ও আধুনিক প্যাকিং ম্যাটেরিয়ালস',
            'subtitle' => 'বাবল র‍্যাপ ও ৫-লেয়ার কার্টন দিয়ে ভঙ্গুর জিনিসপত্রের শতভাগ সুরক্ষা এবং দক্ষ কার্পেন্টার সার্ভিস।',
            'image' => 'hero-slider/xKEW5PJImRgBWMU32KbGa2z2tUbgxZBnG9xVc4W7.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        HomepageCarousel::create([
            'title' => 'ওয়াটারপ্রুফ কাভার্ড ভ্যানে নিরাপদ পরিবহন',
            'subtitle' => 'রোদ-বৃষ্টির কোনো ভয় নেই। সম্পূর্ণ ঢাকা শহর ও আন্তঃজেলা রুটে সময়মতো মালামাল পৌঁছে দেওয়ার নিশ্চয়তা।',
            'image' => 'hero-slider/U4KQ1Rhixd6S6BzGVXy8cq1jBsLbBpUeiF9DqitP.jpg',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 3. About Table Overhaul
        $about = About::first() ?? new About();
        $about->title = 'সহজ শিফটিং সম্পর্কে জানুন';
        $about->page_details = '<p>সহজ শিফটিং হলো আপনার বাসা ও অফিস পরিবর্তনের সবচেয়ে বিশ্বস্ত ও প্রযুক্তি-বান্ধব সঙ্গী। মালামাল সুরক্ষিত প্যাকিং থেকে শুরু করে নতুন ঠিকানায় পৌঁছে নিখুঁতভাবে সাজিয়ে দেওয়া পর্যন্ত প্রতিটি ধাপে আমরা আন্তরিকভাবে পাশে থাকি।</p>';
        $about->details1 = '<p>প্রশিক্ষিত ও বিশ্বস্ত লোডার টিম দ্বারা ভারী আসবাবপত্র, ফ্রিজ, ওয়াশিং মেশিন ও আলমারি অত্যন্ত সতর্কতার সাথে ওঠানামা করা হয়।</p>';
        $about->details2 = '<p>বিশেষ বাবল শিট, ফোম ও কার্টনে ভঙ্গুর তৈজসপত্র ও ইলেকট্রনিক্স মালামালের প্রিমিয়াম প্যাকিং সেবা প্রদান করি।</p>';
        $about->details3 = '<p>দক্ষ এসি টেকনিশিয়ান ও কার্পেন্টার দ্বারা এসি, ফ্যান, গিজার খোলা ও খাট-আলমারি ডিস-অ্যাসেম্বল এবং নতুন ফ্ল্যাটে রি-অ্যাসেম্বল সেবা।</p>';
        $about->details4 = '<p>কোনো প্রকার গোপন চার্জ ছাড়া স্বচ্ছ মূল্যে দ্রুততম সময়ে ঢাকা ও সারাদেশে নিরাপদ পরিবহন।</p>';
        $about->key_values = ['১০০% নিরাপদ ডেলিভারি', 'অভিজ্ঞ ও প্রশিক্ষিত টিম', 'সময়মতো পরিবহন', 'সাশ্রয়ী ও স্বচ্ছ মূল্য'];
        $about->mission = 'গ্রাহকদের জন্য বাসা ও অফিস বদলানোর প্রক্রিয়াকে সর্বোচ্চ নিরাপদ, সময়োপযোগী এবং সাশ্রয়ী করে তোলা।';
        $about->vision = 'বাংলাদেশের প্রতিটি পরিবার ও প্রতিষ্ঠানের কাছে সবচেয়ে নির্ভরযোগ্য এবং স্মার্ট শিফটিং পার্টনার হিসেবে প্রতিষ্ঠিত হওয়া।';
        $about->history = '২০১৮ সালে যাত্রা শুরু করে সহজ শিফটিং আজ ঢাকার অন্যতম প্রধান শিফটিং সেবা প্রদানকারী প্ল্যাটফর্ম হিসেবে ৫,০০০+ পরিবার ও প্রতিষ্ঠানকে সফল সেবা প্রদান করেছে।';
        $about->years_experience = 8;
        $about->establishment_year = 2018;
        $about->save();

        // 4. Counters Overhaul (Shifting Stats)
        Counter::truncate();
        Counter::create(['title' => 'সফল শিফটিং সম্পন্ন', 'value' => 5200, 'status' => true]);
        Counter::create(['title' => 'সন্তুষ্ট পরিবার ও প্রতিষ্ঠান', 'value' => 4850, 'status' => true]);
        Counter::create(['title' => 'দক্ষ লেবার ও টেকনিশিয়ান', 'value' => 65, 'status' => true]);
        Counter::create(['title' => 'নিরাপদ ডেলিভারি রেট (%)', 'value' => 99, 'status' => true]);

        // 5. Testimonials Overhaul (Real Shifting Client Reviews)
        Testimonial::truncate();
        Testimonial::create([
            'name' => 'নাজমুল আহমেদ',
            'designation' => 'ব্যাংক কর্মকর্তা (উত্তরা)',
            'message' => 'অসাধারণ সার্ভিস! ২ বেডরুমের ফ্ল্যাটের সবকিছু মিরপুর থেকে উত্তরায় মাত্র ৫ ঘণ্টায় কোনো দাগ ছাড়া স্থানান্তর করেছে। লেবারদের ব্যবহার অনেক অমায়িক ও পেশাদার ছিল।',
            'rating' => 5,
            'status' => true,
        ]);
        Testimonial::create([
            'name' => 'ফারহানা সুলতানা',
            'designation' => 'এইচআর ডিরেক্টর, আইটি পার্ক (গুলশান)',
            'message' => 'আমাদের ২০ জনের করপোরেট অফিস এক রাতের মধ্যে সম্পূর্ণ নিরাপদে শিফটিং করে দিয়েছে সহজ শিফটিং টিম। প্রতিটি ওয়ার্কস্টেশন সুন্দরভাবে সেটআপ করে দিয়েছে।',
            'rating' => 5,
            'status' => true,
        ]);
        Testimonial::create([
            'name' => 'আরিফুল হক',
            'designation' => 'সফটওয়্যার ইঞ্জিনিয়ার (ধানমন্ডি)',
            'message' => 'এসি খোলা ও ফিটিং সহ ফুল প্যাকেজ নিয়েছিলাম। টেকনিশিয়ান খুবই দক্ষ ছিলেন, গ্যাস লকের কোনো সমস্যা হয়নি এবং নির্ধারিত সময়েই শিফটিং সম্পন্ন হয়েছে।',
            'rating' => 5,
            'status' => true,
        ]);

        // 6. Services Overhaul Matching Client Image 2 Exactly
        $shiftingServices = [
            [
                'title' => 'বাসা ও অফিস শিফটিং',
                'short_description' => 'পুরো বাসা বা অফিস নিরাপদে স্থানান্তর।',
                'description' => 'পুরো বাসা বা অফিসের সমস্ত আসবাবপত্র, ইলেকট্রনিক্স ও তৈজসপত্র অত্যন্ত যত্নসহকারে প্যাক, লোড, পরিবহন ও আনলোড করে নির্দিষ্ট স্থানে সাজিয়ে দেওয়া হয়।',
                'price' => null,
                'price_range' => 'কাস্টম কোটেশন',
                'pricing_type' => 'negotiable',
                'show_price' => false,
                'icon' => 'bi-box-seam-fill',
                'is_addon_for_booking' => false,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'দক্ষ লেবার সার্ভিস',
                'short_description' => 'প্রশিক্ষিত লেবার, দরকার মতো সংখ্যা।',
                'description' => 'ভারী আসবাবপত্র, ফ্রিজ, আলমারি সাবধানে সিঁড়ি দিয়ে ওঠানামার জন্য অভিজ্ঞ ও প্রশিক্ষিত লেবার সরবরাহ করা হয়।',
                'price' => 800.00,
                'price_range' => '৳৮০০ প্রতিজন',
                'pricing_type' => 'fixed',
                'show_price' => true,
                'icon' => 'bi-person-badge-fill',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'ইলেকট্রিক্যাল সার্ভিস',
                'short_description' => 'লাইন ও ফিটিং খোলা-লাগানো।',
                'description' => 'ফ্যান, লাইট, গিজার, আইপিএস, ওয়াটার ফিল্টার ও ঘরের ইলেকট্রিক্যাল লাইন নিরাপদভাবে ডিসকানেক্ট ও পুনরায় সংযোগ।',
                'price' => 800.00,
                'price_range' => '৳৫০০ - ৳১,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-lightning-charge-fill',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'এসি খোলা ও ফিটিং',
                'short_description' => 'এসি নিরাপদে খুলে নতুন জায়গায় বসানো।',
                'description' => 'সার্টিফাইড এসি মেকানিক দ্বারা গ্যাস লক করে এসি খোলা এবং নতুন লোকেশনে গ্যাস চার্জ ও ড্রিলিং সহ প্রফেশনাল ফিটিং।',
                'price' => 2500.00,
                'price_range' => '৳২,০০০ - ৳৩,০০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-snow',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'ফার্নিচার স্থানান্তর',
                'short_description' => 'খাট, সোফা, আলমারি সাবধানে পরিবহন।',
                'description' => 'সেগুন কাঠের ভারী খাট, আলমারি, ডাইনিং টেবিল ও সোফা সেট অত্যন্ত সতর্কতার সাথে সুরক্ষিতভাবে স্থানান্তর।',
                'price' => null,
                'price_range' => 'আলোচনা সাপেক্ষে',
                'pricing_type' => 'negotiable',
                'show_price' => false,
                'icon' => 'bi-house-heart-fill',
                'is_addon_for_booking' => false,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'খোলা ও পুনরায় সেটআপ',
                'short_description' => 'আসবাবপত্র খুলে নতুন জায়গায় আবার সাজানো।',
                'description' => 'অভিজ্ঞ কার্পেন্টার দ্বারা খাট, ড্রেসিং টেবিল, ওয়াল কেবিনেট খুলে পার্টস আলাদা করা এবং নতুন বাসায় নিখুঁতভাবে রি-অ্যাসেম্বল।',
                'price' => 1500.00,
                'price_range' => '৳১,০০০ - ৳২,০০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-tools',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 6,
            ],
            [
                'title' => 'প্যাকিং ও আনপ্যাকিং',
                'short_description' => 'মালামাল গুছিয়ে প্যাক ও খুলে দেওয়া।',
                'description' => 'বাবল শিট, ফোম, স্ট্রেচ ফিল্ম ও শক্ত কার্টন দিয়ে কাঁচের ক্রোকারিজ ও হোম অ্যাপ্লায়েন্স প্যাকিং ও নতুন বাসায় আনপ্যাক করা।',
                'price' => 2000.00,
                'price_range' => '৳১,৫০০ - ৳৩,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-gift-fill',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 7,
            ],
            [
                'title' => 'পিকআপ ভ্যান',
                'short_description' => 'পিকআপ ভ্যানে মালামাল পরিবহন।',
                'description' => '৭ ফিট ছোট পিকআপ এবং ৯ ফিট ও ১৪ ফিট ওয়াটারপ্রুফ কাভার্ড ভ্যানে সম্পূর্ণ নিরাপদ পরিবহন।',
                'price' => 2500.00,
                'price_range' => '৳২,৫০০ - ৳৪,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-truck-flatbed',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($shiftingServices as $srv) {
            Service::updateOrCreate(
                ['title' => $srv['title']],
                array_merge($srv, ['slug' => Str::slug($srv['title'])])
            );
        }
    }
}
