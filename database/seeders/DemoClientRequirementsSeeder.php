<?php

namespace Database\Seeders;

use App\Models\BookingFollowUp;
use App\Models\BookingInvoice;
use App\Models\BookingQuotation;
use App\Models\BookingSupportTicket;
use App\Models\Coupon;
use App\Models\PropertyType;
use App\Models\Setting;
use App\Models\ShiftingBooking;
use App\Models\ShiftingBookingImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoClientRequirementsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Configure Admin Account
        $admin = User::find(1);
        if ($admin) {
            $admin->update([
                'phone' => '01711000001',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]);
        } else {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'phone' => '01711000001',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]);
        }

        // 2. Configure Demo Customers
        $customer1 = User::updateOrCreate(
            ['email' => 'tanvir@gmail.com'],
            [
                'name' => 'তানভীর আহমেদ',
                'phone' => '01711122233',
                'address' => 'বাড়ি # ১২, রোড # ৪, সেক্টর # ৩, উত্তরা, ঢাকা',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        $customer2 = User::updateOrCreate(
            ['email' => 'sadia@gmail.com'],
            [
                'name' => 'সাদিয়া রহমান',
                'phone' => '01822233344',
                'address' => 'ফ্ল্যাট ৪বি, প্লট ১৮, মিরপুর ১০, ঢাকা',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        $customer3 = User::updateOrCreate(
            ['email' => 'corporate@dhaka.com'],
            [
                'name' => 'রফিকুল ইসলাম (Apex IT Ltd)',
                'phone' => '01933344455',
                'address' => 'লেভেল ৫, ডিওএইচএস মহাখালী, ঢাকা',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        // 3. Marketing & Analytics Settings
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'site_name' => 'Sohoz Shifting BD',
                'meta_pixel_id' => '987654321098765',
                'ga4_measurement_id' => 'G-SOHOZ2026',
                'whatsapp_tracking_number' => '8801711122233',
                'enable_marketing_tracking' => true,
            ]);
        }

        // 4. Coupons
        $coupon1 = Coupon::updateOrCreate(
            ['code' => 'SHOHOZ500'],
            [
                'title' => 'নতুন বুকিং বিশেষ ছাড়',
                'discount_type' => 'fixed',
                'discount_value' => 500,
                'min_booking_amount' => 5000,
                'max_discount' => null,
                'usage_limit' => 200,
                'used_count' => 12,
                'starts_at' => now()->subDays(10)->toDateString(),
                'expires_at' => now()->addMonths(3)->toDateString(),
                'is_active' => true,
            ]
        );

        $coupon2 = Coupon::updateOrCreate(
            ['code' => 'DISCOUNT10'],
            [
                'title' => '১০% মহা ছাড় অফার',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_booking_amount' => 4000,
                'max_discount' => 1500,
                'usage_limit' => 100,
                'used_count' => 35,
                'starts_at' => now()->subDays(5)->toDateString(),
                'expires_at' => now()->addMonths(2)->toDateString(),
                'is_active' => true,
            ]
        );

        $coupon3 = Coupon::updateOrCreate(
            ['code' => 'EID2026'],
            [
                'title' => 'ঈদ স্পেশাল ক্যাশব্যাক',
                'discount_type' => 'fixed',
                'discount_value' => 1000,
                'min_booking_amount' => 10000,
                'max_discount' => null,
                'usage_limit' => 50,
                'used_count' => 8,
                'starts_at' => now()->subDays(2)->toDateString(),
                'expires_at' => now()->addMonths(1)->toDateString(),
                'is_active' => true,
            ]
        );

        $types = PropertyType::all()->keyBy('name');
        $type2Bed = $types->get('2 Bedroom Flat') ?? PropertyType::first();
        $type3Bed = $types->get('3+ Bedroom Flat') ?? PropertyType::first();
        $typeStudio = $types->get('Studio / Small House') ?? PropertyType::first();
        $typeOffice = $types->get('Office / Commercial') ?? PropertyType::first();

        // 5. Create 10 Bookings spanning all 10 Status Stages

        // 1. new_request (নতুন বুকিং) - Sadia Rahman
        $b1 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1001'],
            [
                'user_id' => $customer2->id,
                'customer_name' => $customer2->name,
                'customer_phone' => $customer2->phone,
                'customer_email' => $customer2->email,
                'property_type_id' => $type2Bed->id,
                'pickup_address' => 'বাড়ি ৪৪, রোড ৭, সেক্টর ১০, উত্তরা',
                'pickup_area' => 'Uttara',
                'pickup_floor' => 4,
                'pickup_has_lift' => false,
                'delivery_address' => 'রোড ১২, ব্লক ডি, বনানী',
                'delivery_area' => 'Banani',
                'delivery_floor' => 5,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(4)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'pickup_7ft',
                'labour_count' => 3,
                'special_items_json' => ['ac', 'fridge_deep', 'tv_large'],
                'discount_amount' => 500,
                'coupon_id' => $coupon1->id,
                'estimated_cost' => 11500,
                'final_cost' => 11500,
                'advance_amount' => 0,
                'due_amount' => 11500,
                'payment_status' => 'unpaid',
                'status' => 'new_request',
                'utm_source' => 'facebook',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'dhaka_shifting_nov',
            ]
        );

        // 2. contacted (গ্রাহকের সঙ্গে কথা হয়েছে) - Tanvir Ahmed
        $b2 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1002'],
            [
                'user_id' => $customer1->id,
                'customer_name' => $customer1->name,
                'customer_phone' => $customer1->phone,
                'customer_email' => $customer1->email,
                'property_type_id' => $type3Bed->id,
                'pickup_address' => 'হাউজ ৫, রোড ২, ধানমন্ডি',
                'pickup_area' => 'Dhanmondi',
                'pickup_floor' => 2,
                'pickup_has_lift' => true,
                'delivery_address' => 'হাউজ ১৮, সেক্টর ৭, উত্তরা',
                'delivery_area' => 'Uttara',
                'delivery_floor' => 3,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(5)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'open_truck_14ft',
                'labour_count' => 4,
                'special_items_json' => ['ac', 'safe_locker', 'glass_table'],
                'coupon_id' => $coupon2->id,
                'discount_amount' => 1200,
                'estimated_cost' => 16800,
                'final_cost' => 16800,
                'advance_amount' => 0,
                'due_amount' => 16800,
                'payment_status' => 'unpaid',
                'status' => 'contacted',
                'utm_source' => 'google',
                'utm_medium' => 'search',
                'utm_campaign' => 'flat_relocation_dhaka',
            ]
        );

        // 3. assessment_required (যাচাই প্রয়োজন) - Rafiqul Islam
        $b3 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1003'],
            [
                'user_id' => $customer3->id,
                'customer_name' => $customer3->name,
                'customer_phone' => $customer3->phone,
                'customer_email' => $customer3->email,
                'property_type_id' => $typeOffice->id,
                'pickup_address' => 'লেভেল ৪, ডিওএইচএস মহাখালী',
                'pickup_area' => 'Mohakhali DOHS',
                'pickup_floor' => 4,
                'pickup_has_lift' => true,
                'delivery_address' => 'রোড ১১, ব্লক ই, বনানী',
                'delivery_area' => 'Banani',
                'delivery_floor' => 2,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(7)->toDateString(),
                'preferred_time_slot' => 'Afternoon (1 PM - 5 PM)',
                'vehicle_type' => 'covered_van_14ft',
                'labour_count' => 6,
                'special_items_json' => ['safe_locker', 'ac'],
                'estimated_cost' => 22000,
                'final_cost' => 22000,
                'advance_amount' => 0,
                'due_amount' => 22000,
                'payment_status' => 'unpaid',
                'status' => 'assessment_required',
                'utm_source' => 'whatsapp',
                'utm_medium' => 'chat',
                'utm_campaign' => 'corporate_inquiry',
            ]
        );

        // 4. site_visit_scheduled (ভিজিট নির্ধারিত) - Sadia Rahman
        $b4 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1004'],
            [
                'user_id' => $customer2->id,
                'customer_name' => $customer2->name,
                'customer_phone' => $customer2->phone,
                'customer_email' => $customer2->email,
                'property_type_id' => $typeStudio->id,
                'pickup_address' => 'ব্লক সি, বসুন্ধরা আ/এ',
                'pickup_area' => 'Bashundhara',
                'pickup_floor' => 3,
                'pickup_has_lift' => false,
                'delivery_address' => 'গুলশান এভিনিউ, গুলশান ২',
                'delivery_area' => 'Gulshan 2',
                'delivery_floor' => 6,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(6)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'site_visit_date' => now()->addDay()->setHour(11)->setMinute(0),
                'vehicle_type' => 'pickup_7ft',
                'labour_count' => 2,
                'estimated_cost' => 7500,
                'final_cost' => 7500,
                'advance_amount' => 0,
                'due_amount' => 7500,
                'payment_status' => 'unpaid',
                'status' => 'site_visit_scheduled',
                'utm_source' => 'facebook',
                'utm_medium' => 'video_ad',
                'utm_campaign' => 'winter_moving_offer',
            ]
        );

        // 5. quotation_sent (ভাড়া জানানো হয়েছে) - Tanvir Ahmed (Awaiting Customer Approval)
        $b5 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1005'],
            [
                'user_id' => $customer1->id,
                'customer_name' => $customer1->name,
                'customer_phone' => $customer1->phone,
                'customer_email' => $customer1->email,
                'property_type_id' => $type3Bed->id,
                'pickup_address' => 'বাড়ি ১২, রোড ৭, ধানমন্ডি',
                'pickup_area' => 'Dhanmondi',
                'pickup_floor' => 3,
                'pickup_has_lift' => true,
                'delivery_address' => 'রোড ১০, সেক্টর ৪, উত্তরা',
                'delivery_area' => 'Uttara',
                'delivery_floor' => 5,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(3)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'covered_van_14ft',
                'labour_count' => 4,
                'special_items_json' => ['ac', 'fridge_deep', 'glass_table'],
                'estimated_cost' => 19500,
                'final_cost' => 19000,
                'advance_amount' => 0,
                'due_amount' => 19000,
                'payment_status' => 'unpaid',
                'status' => 'quotation_sent',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'dhaka_movers_brand',
            ]
        );

        // 6. customer_approved (গ্রাহক সম্মত) - Rafiqul Islam
        $b6 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1006'],
            [
                'user_id' => $customer3->id,
                'customer_name' => $customer3->name,
                'customer_phone' => $customer3->phone,
                'customer_email' => $customer3->email,
                'property_type_id' => $typeOffice->id,
                'pickup_address' => 'প্লট ৫০, গুলশান লিংক রোড',
                'pickup_area' => 'Tejgaon',
                'pickup_floor' => 2,
                'pickup_has_lift' => true,
                'delivery_address' => 'হাউজ ১৫, কামাল আতাতুর্ক এভিনিউ, বনানী',
                'delivery_area' => 'Banani',
                'delivery_floor' => 4,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(5)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'covered_van_14ft',
                'labour_count' => 6,
                'estimated_cost' => 24000,
                'final_cost' => 24000,
                'advance_amount' => 5000,
                'due_amount' => 19000,
                'payment_status' => 'partial_advance',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'EBL-TRX-998811',
                'status' => 'customer_approved',
                'utm_source' => 'referral',
                'utm_medium' => 'b2b',
                'utm_campaign' => 'corporate_partner',
            ]
        );

        // 7. confirmed (বুকিং নিশ্চিত) - Tanvir Ahmed
        $b7 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1007'],
            [
                'user_id' => $customer1->id,
                'customer_name' => $customer1->name,
                'customer_phone' => $customer1->phone,
                'customer_email' => $customer1->email,
                'property_type_id' => $type2Bed->id,
                'pickup_address' => 'ফ্ল্যাট ৫এ, হাউজ ৯, খিলগাঁও',
                'pickup_area' => 'Khilgaon',
                'pickup_floor' => 5,
                'pickup_has_lift' => true,
                'delivery_address' => 'রোড ৩, আফতাবনগর',
                'delivery_area' => 'Aftabnagar',
                'delivery_floor' => 2,
                'delivery_has_lift' => true,
                'shifting_date' => now()->addDays(2)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'pickup_7ft',
                'labour_count' => 3,
                'estimated_cost' => 9500,
                'final_cost' => 9500,
                'advance_amount' => 3000,
                'due_amount' => 6500,
                'payment_status' => 'partial_advance',
                'payment_method' => 'bKash',
                'transaction_id' => 'BK-9A7B3C1',
                'assigned_supervisor_name' => 'মোঃ রফিক হোসেন',
                'assigned_supervisor_phone' => '01712998877',
                'driver_name' => 'আব্দুল করিম',
                'driver_phone' => '01819665544',
                'driver_truck_number' => 'ঢাকা মেট্রো-ন ১১-৮৮৯৯',
                'status' => 'confirmed',
                'utm_source' => 'facebook',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'flat_relocation_dhaka',
            ]
        );

        // 8. in_progress (কাজ চলছে) - Tanvir Ahmed (Live Moving Today!)
        $b8 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1008'],
            [
                'user_id' => $customer1->id,
                'customer_name' => $customer1->name,
                'customer_phone' => $customer1->phone,
                'customer_email' => $customer1->email,
                'property_type_id' => $typeStudio->id,
                'pickup_address' => 'হাউজ ১৫, রোড ৪, নিকুঞ্জ ২',
                'pickup_area' => 'Nikunja',
                'pickup_floor' => 2,
                'pickup_has_lift' => true,
                'delivery_address' => 'সেক্টর ৯, উত্তরা',
                'delivery_area' => 'Uttara',
                'delivery_floor' => 4,
                'delivery_has_lift' => true,
                'shifting_date' => now()->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'pickup_7ft',
                'labour_count' => 2,
                'special_items_json' => ['ac'],
                'estimated_cost' => 7800,
                'final_cost' => 7800,
                'advance_amount' => 3000,
                'due_amount' => 4800,
                'payment_status' => 'partial_advance',
                'payment_method' => 'Nagad',
                'transaction_id' => 'NGD-66554433',
                'assigned_supervisor_name' => 'কামাল হোসেন',
                'assigned_supervisor_phone' => '01811223344',
                'driver_name' => 'সাইফুল ইসলাম',
                'driver_phone' => '01715887766',
                'driver_truck_number' => 'ঢাকা মেট্রো-ট ১৩-৪৪৫৫',
                'status' => 'in_progress',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'dhaka_movers_brand',
            ]
        );

        // 9. completed (কাজ সম্পন্ন) - Tanvir Ahmed (Paid in full, Invoice ready)
        $b9 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1009'],
            [
                'user_id' => $customer1->id,
                'customer_name' => $customer1->name,
                'customer_phone' => $customer1->phone,
                'customer_email' => $customer1->email,
                'property_type_id' => $type3Bed->id,
                'pickup_address' => 'হাউজ ১২, রোড ৫, মিরপুর ডিওএইচএস',
                'pickup_area' => 'Mirpur DOHS',
                'pickup_floor' => 3,
                'pickup_has_lift' => true,
                'delivery_address' => 'হাউজ ৮, রোড ১১, বনানী',
                'delivery_area' => 'Banani',
                'delivery_floor' => 4,
                'delivery_has_lift' => true,
                'shifting_date' => now()->subDays(2)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'covered_van_14ft',
                'labour_count' => 4,
                'special_items_json' => ['ac', 'fridge_deep', 'glass_table'],
                'coupon_id' => $coupon1->id,
                'discount_amount' => 500,
                'estimated_cost' => 16500,
                'final_cost' => 16500,
                'advance_amount' => 5000,
                'due_amount' => 0,
                'payment_status' => 'paid_in_full',
                'payment_method' => 'Cash on Delivery + bKash',
                'transaction_id' => 'BK-FULL-889922',
                'assigned_supervisor_name' => 'কামাল হোসেন',
                'assigned_supervisor_phone' => '01811223344',
                'driver_name' => 'আবুল কালাম',
                'driver_phone' => '01712334455',
                'driver_truck_number' => 'ঢাকা মেট্রো-ট ১৪-৭৭৮৮',
                'status' => 'completed',
                'utm_source' => 'facebook',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'dhaka_shifting_nov',
            ]
        );

        // 10. cancelled (বাতিল) - Sadia Rahman
        $b10 = ShiftingBooking::updateOrCreate(
            ['booking_code' => 'SH-2026-1010'],
            [
                'user_id' => $customer2->id,
                'customer_name' => $customer2->name,
                'customer_phone' => $customer2->phone,
                'customer_email' => $customer2->email,
                'property_type_id' => $type2Bed->id,
                'pickup_address' => 'রোড ৩, ধানমন্ডি',
                'pickup_area' => 'Dhanmondi',
                'pickup_floor' => 1,
                'pickup_has_lift' => true,
                'delivery_address' => 'রোড ১৫, মোহাম্মদপুর',
                'delivery_area' => 'Mohammadpur',
                'delivery_floor' => 2,
                'delivery_has_lift' => false,
                'shifting_date' => now()->addDays(8)->toDateString(),
                'preferred_time_slot' => 'Morning (8 AM - 12 PM)',
                'vehicle_type' => 'pickup_7ft',
                'labour_count' => 2,
                'estimated_cost' => 8500,
                'final_cost' => 8500,
                'advance_amount' => 0,
                'due_amount' => 8500,
                'payment_status' => 'unpaid',
                'status' => 'cancelled',
                'cancellation_reason' => 'গ্রাহক বাসার চুক্তি পিছিয়ে যাওয়ায় বুকিং বাতিল করেছেন।',
                'utm_source' => 'organic',
                'utm_medium' => 'google_organic',
            ]
        );

        // 6. Add Official Quotations
        // For b5 (quotation_sent)
        BookingQuotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-1005'],
            [
                'shifting_booking_id' => $b5->id,
                'line_items' => [
                    ['description' => '৩ বেডরুম ফ্ল্যাট সামগ্রিক শিফটিং (ধানমন্ডি হতে উত্তরা)', 'quantity' => 1, 'rate' => 11000, 'total' => 11000],
                    ['description' => '১৪ ফুট কাভার্ড ভ্যান ট্রান্সপোর্ট ও ফুয়েল', 'quantity' => 1, 'rate' => 3500, 'total' => 3500],
                    ['description' => 'এক্সপার্ট লোডিং ও আনলোডিং লেবার সার্ভিস (৪ জন)', 'quantity' => 4, 'rate' => 800, 'total' => 3200],
                    ['description' => '১.৫ টন স্প্লিট এসি খোলা ও মাস্টার ফিটিং', 'quantity' => 1, 'rate' => 2500, 'total' => 2500],
                    ['description' => 'গ্লাস ডাইনিং টেবিল ও ফ্রেম সেফটি বাবল র‍্যাপিং', 'quantity' => 1, 'rate' => 1200, 'total' => 1200],
                ],
                'subtotal' => 21400,
                'discount_amount' => 2400,
                'total_amount' => 19000,
                'advance_required' => 5000,
                'status' => 'sent',
                'notes' => 'অভিজ্ঞ টেকনিশিয়ান দ্বারা এসি খোলা ও ফিটিং সম্পন্ন করা হবে। সমস্ত মালামাল নিরাপদ প্যাকেজিং সহ শিফট করা হবে।',
                'terms_conditions' => '১. বুকিং নিশ্চিত করতে অগ্রিম ৫,০০০ টাকা প্রদান করুন। ২. অবশিষ্ট ১৪,০০০ টাকা কাজ শেষ হলে নগদ বা bKash-এ পরিশোধযোগ্য।',
                'sent_at' => now()->subHours(4),
            ]
        );

        // For b6 (customer_approved)
        BookingQuotation::updateOrCreate(
            ['quotation_number' => 'QT-2026-1006'],
            [
                'shifting_booking_id' => $b6->id,
                'line_items' => [
                    ['description' => 'কর্পোরেট অফিস ফার্নিচার ও ওয়ার্কস্টেশন শিফটিং', 'quantity' => 1, 'rate' => 15000, 'total' => 15000],
                    ['description' => 'হেভি আয়রন সেফ ও লকার স্পেশাল মুভিং ইকুইপমেন্ট', 'quantity' => 1, 'rate' => 3500, 'total' => 3500],
                    ['description' => '১৪ ফুট কাভার্ড ভ্যান ২ টি ট্রিপ', 'quantity' => 2, 'rate' => 3500, 'total' => 7000],
                ],
                'subtotal' => 25500,
                'discount_amount' => 1500,
                'total_amount' => 24000,
                'advance_required' => 5000,
                'status' => 'approved',
                'notes' => 'ক্লায়েন্ট অফিস থেকে কোটেশন অনুমোদিত হয়েছে। ব্যাংক ট্রান্সফারের মাধ্যমে অ্যাডভান্স সম্পন্ন।',
                'sent_at' => now()->subDays(1),
                'approved_at' => now()->subHours(12),
            ]
        );

        // 7. Add Official Invoices
        // For b9 (completed, paid in full)
        BookingInvoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-0001'],
            [
                'shifting_booking_id' => $b9->id,
                'invoice_date' => now()->subDays(2)->toDateString(),
                'line_items' => [
                    ['description' => '৩ বেডরুম ফ্ল্যাট সামগ্রিক শিফটিং (মিরপুর DOHS হতে বনানী)', 'quantity' => 1, 'rate' => 11000, 'total' => 11000],
                    ['description' => '১৪ ফুট কাভার্ড ভ্যান ট্রান্সপোর্টেশন', 'quantity' => 1, 'rate' => 3000, 'total' => 3000],
                    ['description' => '৪ জন অভিজ্ঞ লেবার সার্ভিস', 'quantity' => 4, 'rate' => 750, 'total' => 3000],
                    ['description' => 'প্রোটেক্টিভ বাবল ও ফোম র‍্যাপিং মেটেরিয়াল', 'quantity' => 1, 'rate' => 1500, 'total' => 1500],
                ],
                'subtotal' => 18500,
                'discount_amount' => 2000,
                'total_amount' => 16500,
                'advance_paid' => 16500,
                'due_balance' => 0,
                'payment_status' => 'paid_in_full',
                'payment_method' => 'Cash + bKash (BK-FULL-889922)',
                'notes' => 'সম্পূর্ণ শিফটিং সফলভাবে সম্পন্ন হয়েছে। সকল মালামাল অক্ষত অবস্থায় ডেলিভারি দেওয়া হয়েছে।',
            ]
        );

        // For b7 (confirmed, partial advance paid)
        BookingInvoice::updateOrCreate(
            ['invoice_number' => 'INV-2026-0002'],
            [
                'shifting_booking_id' => $b7->id,
                'invoice_date' => now()->subDay()->toDateString(),
                'line_items' => [
                    ['description' => '২ বেডরুম ফ্ল্যাট শিফটিং (খিলগাঁও হতে আফতাবনগর)', 'quantity' => 1, 'rate' => 7500, 'total' => 7500],
                    ['description' => 'পিকআপ ভ্যান ট্রান্সপোর্ট সার্ভিস', 'quantity' => 1, 'rate' => 2500, 'total' => 2500],
                ],
                'subtotal' => 10000,
                'discount_amount' => 500,
                'total_amount' => 9500,
                'advance_paid' => 3000,
                'due_balance' => 6500,
                'payment_status' => 'partial_advance',
                'payment_method' => 'bKash (BK-9A7B3C1)',
                'notes' => 'অ্যাডভান্স ৩,০০০ টাকা গৃহীত হয়েছে। অবশিষ্ট ৬,৫০০ টাকা শিফটিং সমাপ্তির পর পরিশোধযোগ্য।',
            ]
        );

        // 8. CRM Follow-Up Logs
        BookingFollowUp::create([
            'shifting_booking_id' => $b2->id,
            'admin_user_id' => $admin->id,
            'contact_type' => 'phone_call',
            'notes' => 'গ্রাহক তানভীর সাহেবের সাথে ফোনে বিস্তারিত আলোচনা হয়েছে। ৩ বেডরুমের ফার্নিচার ও ২টি এসি রয়েছে। আগামী পরশু সাইট ভিজিটের জন্য সময় নির্ধারিত করার অনুরোধ করেছেন।',
            'next_follow_up_date' => now()->addDay(),
            'outcome' => 'interested',
        ]);

        BookingFollowUp::create([
            'shifting_booking_id' => $b3->id,
            'admin_user_id' => $admin->id,
            'contact_type' => 'whatsapp',
            'notes' => 'Apex IT অফিসের সাথে হোয়াটসঅ্যাপে যোগাযোগ করা হয়েছে। তাদের হেভি সার্ভার রেক ও আয়রন লকার রয়েছে। টেকনিক্যাল টিম নিয়ে ভিজিটে যেতে হবে।',
            'next_follow_up_date' => now()->addDays(2),
            'outcome' => 'quote_requested',
        ]);

        BookingFollowUp::create([
            'shifting_booking_id' => $b4->id,
            'admin_user_id' => $admin->id,
            'contact_type' => 'phone_call',
            'notes' => 'গ্রাহক সাদিয়া রহমানের সাথে ফোনে যোগাযোগ করে আগামীকাল সকাল ১১টায় সাইট ভিজিট কনফার্ম করা হয়েছে। সুপারভাইজার রফিক সাহেবকে অ্যাসাইন করা হয়েছে।',
            'next_follow_up_date' => now()->addDay(),
            'outcome' => 'confirmed',
        ]);

        // 9. Customer Support / Inquiry Tickets
        BookingSupportTicket::updateOrCreate(
            ['ticket_number' => 'TCK-2026-1008'],
            [
                'shifting_booking_id' => $b8->id,
                'user_id' => $customer1->id,
                'subject' => 'শিফটিং এর সময় অতিরিক্ত লেবার বাড়ানো সংক্রান্ত প্রশ্ন',
                'category' => 'query',
                'priority' => 'high',
                'status' => 'resolved',
                'messages' => [
                    [
                        'sender' => 'customer',
                        'sender_name' => $customer1->name,
                        'message' => 'শুভ সকাল, আজ আমাদের নিকুঞ্জ থেকে উত্তরায় শিফটিং চলছে। আমরা কি ড্রপ পয়েন্টে আনলোডিংয়ের জন্য আরও ১ জন অতিরিক্ত লেবার নিতে পারব?',
                        'timestamp' => now()->subMinutes(45)->format('Y-m-d H:i:s'),
                    ],
                    [
                        'sender' => 'admin',
                        'sender_name' => 'সহজ শিফটিং সাপোর্ট টিম',
                        'message' => 'জি তানভীর সাহেব, আমাদের অন-ডিউটি সুপারভাইজার কামাল সাহেবকে (01811223344) এখনই জানিয়ে দেওয়া হয়েছে। আনলোডিং পয়েন্টে অতিরিক্ত একজন দক্ষ লেবার যুক্ত করা হবে।',
                        'timestamp' => now()->subMinutes(15)->format('Y-m-d H:i:s'),
                    ]
                ],
            ]
        );

        // 10. Demo Luggage Images for Bookings
        ShiftingBookingImage::updateOrCreate(
            ['shifting_booking_id' => $b1->id, 'image_path' => 'booking_images/living_room_sample.jpg'],
            ['caption' => 'ড্রয়িং রুমের সোফা ও টিভি ইউনিট', 'file_size' => 204800]
        );
        ShiftingBookingImage::updateOrCreate(
            ['shifting_booking_id' => $b1->id, 'image_path' => 'booking_images/master_bed_sample.jpg'],
            ['caption' => 'মাস্টার বেডরুমের ওয়ারড্রব ও খাট', 'file_size' => 312000]
        );
        ShiftingBookingImage::updateOrCreate(
            ['shifting_booking_id' => $b5->id, 'image_path' => 'booking_images/glass_dining_sample.jpg'],
            ['caption' => '৬ সিটের কাচের ডাইনিং টেবিল', 'file_size' => 189000]
        );

        // 11. Booking Services
        \App\Models\ShiftingBookingService::updateOrCreate(
            ['shifting_booking_id' => $b1->id, 'service_id' => 15],
            ['service_name' => 'প্যাকিং ও আনপ্যাকিং', 'unit_price' => 2000, 'quantity' => 1, 'total_price' => 2000]
        );
        \App\Models\ShiftingBookingService::updateOrCreate(
            ['shifting_booking_id' => $b1->id, 'service_id' => 12],
            ['service_name' => 'এসি খোলা ও ফিটিং', 'unit_price' => 2500, 'quantity' => 1, 'total_price' => 2500]
        );
        \App\Models\ShiftingBookingService::updateOrCreate(
            ['shifting_booking_id' => $b2->id, 'service_id' => 10],
            ['service_name' => 'দক্ষ লেবার সার্ভিস', 'unit_price' => 800, 'quantity' => 4, 'total_price' => 3200]
        );
        \App\Models\ShiftingBookingService::updateOrCreate(
            ['shifting_booking_id' => $b5->id, 'service_id' => 15],
            ['service_name' => 'প্যাকিং ও আনপ্যাকিং', 'unit_price' => 2000, 'quantity' => 1, 'total_price' => 2000]
        );
        \App\Models\ShiftingBookingService::updateOrCreate(
            ['shifting_booking_id' => $b5->id, 'service_id' => 12],
            ['service_name' => 'এসি খোলা ও ফিটিং', 'unit_price' => 2500, 'quantity' => 2, 'total_price' => 5000]
        );
    }
}
