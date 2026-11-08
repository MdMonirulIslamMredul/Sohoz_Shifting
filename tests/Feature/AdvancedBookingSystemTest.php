<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\PropertyType;
use App\Models\Service;
use App\Models\Setting;
use App\Models\ShiftingBooking;
use App\Models\ShiftingBookingImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdvancedBookingSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected PropertyType $propertyType;
    protected Service $packingService;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        Setting::create(['site_name' => 'Sohoz Shifting']);

        $this->customer = User::create([
            'name' => 'Farhan Chowdhury',
            'phone' => '01755555555',
            'email' => 'farhan@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        $this->propertyType = PropertyType::create([
            'name' => '3 Bedroom Apartment',
            'name_bn' => '৩ বেডরুম',
            'base_price' => 8000,
            'is_active' => true,
        ]);

        $this->packingService = Service::create([
            'title' => 'মাস্টার বাবল প্যাকিং',
            'slug' => 'master-bubble-packing',
            'price' => 2500,
            'status' => true,
            'category' => 'packing',
        ]);
    }

    public function test_coupon_check_endpoint(): void
    {
        $validCoupon = Coupon::create([
            'code' => 'SAVE1000',
            'discount_type' => 'fixed',
            'discount_value' => 1000,
            'min_booking_amount' => 6000,
            'is_active' => true,
        ]);

        $expiredCoupon = Coupon::create([
            'code' => 'EXPIRED',
            'discount_type' => 'fixed',
            'discount_value' => 500,
            'expires_at' => now()->subDay(),
            'is_active' => true,
        ]);

        // 1. Valid coupon check
        $resValid = $this->actingAs($this->customer)->postJson(route('bookings.check-coupon'), [
            'code' => 'SAVE1000',
            'amount' => 8000,
        ]);
        $resValid->assertStatus(200);
        $resValid->assertJson([
            'success' => true,
            'discount' => 1000,
        ]);

        // 2. Minimum amount not met
        $resMin = $this->actingAs($this->customer)->postJson(route('bookings.check-coupon'), [
            'code' => 'SAVE1000',
            'amount' => 4000,
        ]);
        $resMin->assertStatus(422);
        $resMin->assertJson([
            'success' => false,
        ]);

        // 3. Expired coupon
        $resExp = $this->actingAs($this->customer)->postJson(route('bookings.check-coupon'), [
            'code' => 'EXPIRED',
            'amount' => 8000,
        ]);
        $resExp->assertStatus(422);

        // 4. Invalid non-existent code
        $resInvalid = $this->actingAs($this->customer)->postJson(route('bookings.check-coupon'), [
            'code' => 'RANDOMINVALID',
            'amount' => 8000,
        ]);
        $resInvalid->assertStatus(422);
    }

    public function test_booking_submission_with_luggage_photos_and_special_items(): void
    {
        $file1 = UploadedFile::fake()->image('living_room.jpg', 600, 400);
        $file2 = UploadedFile::fake()->image('bedroom_luggage.png', 800, 600);

        $coupon = Coupon::create([
            'code' => 'DISC500',
            'discount_type' => 'fixed',
            'discount_value' => 500,
            'min_booking_amount' => 5000,
            'is_active' => true,
        ]);

        $postData = [
            'customer_name' => 'Farhan Chowdhury',
            'customer_phone' => '01755555555',
            'customer_email' => 'farhan@test.com',
            'customer_whatsapp' => '01755555555',
            'property_type_selection' => (string) $this->propertyType->id,
            'pickup_address' => 'House 15, Road 7',
            'pickup_area' => 'Banani',
            'pickup_floor' => 3,
            'pickup_has_lift' => '0',
            'delivery_address' => 'Apt 5B, Road 11',
            'delivery_area' => 'Baridhara',
            'delivery_floor' => 2,
            'delivery_has_lift' => '1',
            'shifting_date' => now()->addDays(2)->format('Y-m-d'),
            'preferred_time_slot' => 'সকাল (৮টা - ১২টা)',
            'vehicle_type' => 'covered_14ft',
            'labour_count' => 4,
            'special_items' => [
                'স্প্লিট এসি (Split AC)',
                'হেভি লোহার সিন্দুক / ভল্ট (Locker/Safe)',
                'পিয়ানো / মিউজিক্যাল ইন্সট্রুমেন্ট',
            ],
            'selected_services' => [$this->packingService->id],
            'coupon_code' => 'DISC500',
            'luggage_images' => [$file1, $file2],
            'custom_service_requests' => 'Handle glass dining carefully',
        ];

        // Simulate incoming UTM parameters via session
        session([
            'utm_source' => 'facebook',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'winter_shifting_promo',
        ]);

        $res = $this->actingAs($this->customer)->post(route('bookings.store'), $postData);

        $booking = ShiftingBooking::where('customer_phone', '01755555555')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('new_request', $booking->status);
        $this->assertEquals($coupon->id, $booking->coupon_id);
        $this->assertEquals(500, $booking->discount_amount);
        $this->assertEquals('facebook', $booking->utm_source);
        $this->assertEquals('winter_shifting_promo', $booking->utm_campaign);

        // Verify images saved in storage and database
        $this->assertCount(2, $booking->images);
        foreach ($booking->images as $imgRecord) {
            Storage::disk('public')->assertExists($imgRecord->image_path);
        }

        // Verify special items JSON saved
        $this->assertCount(3, $booking->special_items_json);

        $res->assertRedirect(route('bookings.confirmation', $booking->booking_code));
    }

    public function test_marketing_attribution_middleware_captures_utm_tags(): void
    {
        $this->get('/?utm_source=google&utm_medium=search&utm_campaign=dhaka_shifting');

        $this->assertEquals('google', session('utm_source'));
        $this->assertEquals('search', session('utm_medium'));
        $this->assertEquals('dhaka_shifting', session('utm_campaign'));
    }

    public function test_public_booking_tracking_by_code_and_phone(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SH-TRACK-100',
            'customer_name' => 'Tracking User',
            'customer_phone' => '01988888888',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Mirpur',
            'pickup_area' => 'Mirpur',
            'pickup_floor' => 1,
            'delivery_address' => 'Uttara',
            'delivery_area' => 'Uttara',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'confirmed',
        ]);

        // 1. Search by Code
        $resCode = $this->get(route('bookings.track', ['search' => 'SH-TRACK-100']));
        $resCode->assertStatus(200);
        $resCode->assertSee('SH-TRACK-100');
        $resCode->assertSee('Tracking User');

        // 2. Search by Phone
        $resPhone = $this->get(route('bookings.track', ['search' => '01988888888']));
        $resPhone->assertStatus(200);
        $resPhone->assertSee('SH-TRACK-100');
        $resPhone->assertSee('Tracking User');
    }
}

