<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SohozShiftingServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'বাসা ও অফিস শিফটিং',
                'short_description' => 'সম্পূর্ণ বাসা ও অফিসের আসবাবপত্র নিরাপদে এক স্থান থেকে অন্য স্থানে স্থানান্তর।',
                'description' => 'আমাদের দক্ষ টিম আপনার বাসা ও অফিসের মালামাল অত্যন্ত যত্নসহকারে প্যাক, লোড, পরিবহন ও আনলোড করে নির্দিষ্ট স্থানে সাজিয়ে দেয়। সম্পূর্ণ চাপমুক্ত শিফটিংয়ের নিশ্চয়তা।',
                'price' => null,
                'price_range' => 'কাস্টম কোটেশন',
                'pricing_type' => 'negotiable',
                'show_price' => false,
                'icon' => 'bi-truck',
                'is_addon_for_booking' => false,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'দক্ষ লেবার সার্ভিস',
                'short_description' => 'ভারী আসবাবপত্র তোলা ও নামানোর জন্য অভিজ্ঞ ও বিশ্বস্ত লোডিং-আনলোডিং শ্রমিক।',
                'description' => 'সিঁড়ি দিয়ে ভারী ফার্নিচার, ফ্রিজ, ওয়াশিং মেশিন নিরাপদে ওঠানামার জন্য অভিজ্ঞ লেবার টিম সরবরাহ করা হয়। কোনো মালামাল ক্ষতিগ্রস্ত হওয়ার ঝুঁকি থাকে না।',
                'price' => 1000.00,
                'price_range' => '৳৮০০ - ৳১,২০০ প্রতিজন',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-people',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'ইলেকট্রিক্যাল সার্ভিস',
                'short_description' => 'ফ্যান, লাইট, গিজার, আইপিএস ও ওয়াটার ফিল্টার খোলা ও নতুন বাসায় সংযোগ।',
                'description' => 'সার্টিফাইড ইলেকট্রিশিয়ান দ্বারা নতুন ও পুরাতন বাসার সুইচবোর্ড, ফ্যান, ঝাড়বাতি, গিজার এবং হোম অ্যাপ্লায়েন্স নিরাপদে আনইনস্টল ও ইনস্টলেশন সেবা।',
                'price' => 800.00,
                'price_range' => '৳৫০০ - ৳১,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-lightning-charge',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'এসি খোলা ও ফিটিং',
                'short_description' => 'প্রফেশনাল এসি টেকনিশিয়ান দ্বারা গ্যাস লক সহ এসি নিরাপদ ডিসকানেক্ট ও পুনরায় ফিটিং।',
                'description' => 'ইনভার্টার ও নন-ইনভার্টার স্প্লিট এসি গ্যাস লক করে খোলা, নতুন লোকেশনে ব্র্যাকেট ড্রিলিং ও পাইপিং সহ নিখুঁত ফিটিং সেবা।',
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
                'short_description' => 'খাট, আলমারি, সোফা, ডাইনিং টেবিল সতর্কতার সাথে স্থানান্তর ও সঠিক জায়গায় বসানো।',
                'description' => 'বড় সাইজের ভারী ফার্নিচার যাতে দেয়ালে বা দরজায় ঘষা না লাগে সেভাবে বিশেষ গ্রিপার ও বেল্ট ব্যবহার করে সতর্কতার সাথে বহন করা হয়।',
                'price' => null,
                'price_range' => 'আলোচনা সাপেক্ষে',
                'pricing_type' => 'negotiable',
                'show_price' => false,
                'icon' => 'bi-box-seam',
                'is_addon_for_booking' => false,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'খোলা ও পুনরায় সেটআপ',
                'short_description' => 'অভিজ্ঞ মিস্ত্রি দ্বারা ওয়ারড্রব, খাট ও ফার্নিচার নাট-বল্টু খুলে নতুন বাসায় অ্যাসেম্বল।',
                'description' => 'মডার্ন বোর্ডের ফার্নিচার এবং সেগুন কাঠের খাট/আলমিরা নিখুঁতভাবে ডিস-অ্যাসেম্বল এবং নতুন ফ্ল্যাটে অক্ষতভাবে রি-অ্যাসেম্বল করার কার্পেন্টার সার্ভিস।',
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
                'short_description' => 'বাবল র‍্যাপ, কার্টন বক্স ও ফোম দিয়ে ভঙ্গুর তৈজসপত্র ও কাঁচের জিনিসপত্র প্রিমিয়াম প্যাকিং।',
                'description' => 'ক্রোকারিজ, এলইডি টিভি, কম্পিউটার ও ডেকোরেটিভ আইটেম যাতে জার্নিতে ভেঙে না যায় সেজন্য ৫-লেয়ার কার্টন ও বাবল শিট দিয়ে সুরক্ষিত প্যাকিং।',
                'price' => 2000.00,
                'price_range' => '৳১,৫০০ - ৳৩,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-shield-check',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 7,
            ],
            [
                'title' => 'পিকআপ ভ্যান / ট্রাক',
                'short_description' => '৭ ফিট ছোট পিকআপ, ৯ ফিট ও ১৪ ফিট ওয়াটারপ্রুফ কাভার্ড ভ্যান পরিবহন সেবা।',
                'description' => 'বৃষ্টি বা রোদে মালামালের কোনো ক্ষতি হবে না। জিপিএস ট্র্যাকিংযুক্ত পরিষ্কার ও মানসম্মত কাভার্ড ভ্যানে নিরাপদে মালামাল গন্তব্যে পৌঁছানো হয়।',
                'price' => 3000.00,
                'price_range' => '৳২,০০০ - ৳৪,৫০০',
                'pricing_type' => 'range',
                'show_price' => true,
                'icon' => 'bi-truck-flatbed',
                'is_addon_for_booking' => true,
                'is_featured' => true,
                'status' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => Str::slug($service['title'])],
                $service
            );
        }
    }
}
