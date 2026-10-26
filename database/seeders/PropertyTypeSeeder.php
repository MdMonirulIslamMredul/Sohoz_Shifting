<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Studio / Small House',
                'name_bn' => 'স্টুডিও / ছোট বাসা',
                'base_price' => 3500.00,
                'icon' => 'bi-house',
                'description' => 'একক ব্যক্তি বা কাপলদের ছোট বাসা ও ব্যাচেলর রুম শিফটিং',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => '1 Bedroom Flat',
                'name_bn' => '১ বেডরুম',
                'base_price' => 5000.00,
                'icon' => 'bi-door-closed',
                'description' => '১ বেডরুম, ডাইনিং ও রান্নাঘর সহ সম্পূর্ণ ফ্ল্যাট শিফটিং',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => '2 Bedroom Flat',
                'name_bn' => '২ বেডরুম',
                'base_price' => 7500.00,
                'icon' => 'bi-house-door',
                'description' => '২ বেডরুমের স্ট্যান্ডার্ড ফ্যামিলি ফ্ল্যাট শিফটিং ও ফার্নিচার লোডিং',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => '3+ Bedroom Flat',
                'name_bn' => '৩+ বেডরুম',
                'base_price' => 11000.00,
                'icon' => 'bi-building',
                'description' => '৩ বা ততোধিক বেডরুমের বড় ফ্যামিলি অ্যাপার্টমেন্ট ও ডুপ্লেক্স শিফটিং',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Office / Commercial',
                'name_bn' => 'অফিস / বাণিজ্যিক প্রতিষ্ঠান',
                'base_price' => 12000.00,
                'icon' => 'bi-briefcase',
                'description' => 'করপোরেট অফিস, ওয়ার্কস্টেশন, সার্ভার ও বাণিজ্যিক মালামাল স্থানান্তর',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Others / Custom',
                'name_bn' => 'অন্যান্য / কাস্টম',
                'base_price' => null,
                'icon' => 'bi-grid',
                'description' => 'গোডাউন, শোরুম, সাবলেট বা যেকোনো বিশেষ প্রপার্টি',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            PropertyType::updateOrCreate(
                ['slug' => Str::slug($type['name'])],
                $type
            );
        }
    }
}
