<?php

namespace Tests\Feature;

use App\Models\PropertyType;
use App\Models\Service;
use App\Models\Setting;
use App\Models\ShiftingBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShiftingBookingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected PropertyType $propertyType;
    protected Service $service1;
    protected Service $service2;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'site_name' => 'Sohoz Shifting',
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_shifting@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $this->propertyType = PropertyType::create([
            'name' => '2 Bedroom Flat',
            'name_bn' => '২ বেডরুম',
            'base_price' => 7500.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->service1 = Service::create([
            'title' => 'এসি খোলা ও ফিটিং',
            'slug' => 'ac-uninstallation-fitting',
            'price' => 2000.00,
            'status' => true,
            'sort_order' => 1,
        ]);

        $this->service2 = Service::create([
            'title' => 'ইলেকট্রিক্যাল সার্ভিস',
            'slug' => 'electrical-service',
            'price' => 1200.00,
            'status' => true,
            'sort_order' => 2,
        ]);
    }

    public function test_booking_create_page_renders_successfully(): void
    {
        $response = $this->get(route('bookings.create'));

        $response->assertStatus(200);
        $response->assertSee('২ বেডরুম');
        $response->assertSee('এসি খোলা ও ফিটিং');
        $response->assertSee('ইলেকট্রিক্যাল সার্ভিস');
    }

    public function test_customer_can_submit_booking_with_standard_property_type(): void
    {
        $postData = [
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01700123456',
            'customer_email' => 'tanvir@gmail.com',
            'customer_whatsapp' => '01700123456',
            'property_type_selection' => (string) $this->propertyType->id,
            'pickup_address' => 'বাড়ি ১২, রোড ৪',
            'pickup_area' => 'মিরপুর ১০',
            'pickup_floor' => 2,
            'pickup_has_lift' => '0',
            'delivery_address' => 'ফ্ল্যাট ৪বি, বাড়ি ১৮',
            'delivery_area' => 'ধানমন্ডি ৩২',
            'delivery_floor' => 4,
            'delivery_has_lift' => '1',
            'shifting_date' => now()->addDays(2)->format('Y-m-d'),
            'preferred_time_slot' => 'সকাল (৮টা - ১২টা)',
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 3,
            'selected_services' => [$this->service1->id, $this->service2->id],
            'custom_service_requests' => 'কাঁচের আসবাবপত্র রয়েছে',
            'special_notes' => 'সাবধানে লোড করবেন',
        ];

        $response = $this->post(route('bookings.store'), $postData);

        $this->assertDatabaseHas('shifting_bookings', [
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01700123456',
            'property_type_id' => $this->propertyType->id,
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 3,
            'status' => 'pending',
        ]);

        $booking = ShiftingBooking::where('customer_phone', '01700123456')->first();
        $this->assertNotNull($booking);
        $this->assertStringStartsWith('SH-', $booking->booking_code);
        $this->assertCount(2, $booking->bookingServices);

        $response->assertRedirect(route('bookings.confirmation', $booking->booking_code));
    }

    public function test_customer_can_submit_booking_with_custom_property_type(): void
    {
        $postData = [
            'customer_name' => 'রাকিব হাসান',
            'customer_phone' => '01800987654',
            'property_type_selection' => 'custom',
            'custom_property_type' => '৩ তলা কমার্শিয়াল গোডাউন',
            'pickup_address' => 'তেজগাঁও শিল্প এলাকা',
            'pickup_area' => 'তেজগাঁও',
            'pickup_floor' => 1,
            'delivery_address' => 'টঙ্গী বিসিক',
            'delivery_area' => 'টঙ্গী',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(1)->format('Y-m-d'),
            'vehicle_type' => 'covered_14ft',
            'labour_count' => 4,
        ];

        $response = $this->post(route('bookings.store'), $postData);

        $this->assertDatabaseHas('shifting_bookings', [
            'customer_name' => 'রাকিব হাসান',
            'custom_property_type' => '৩ তলা কমার্শিয়াল গোডাউন',
            'property_type_id' => null,
            'status' => 'pending',
        ]);

        $booking = ShiftingBooking::where('customer_phone', '01800987654')->first();
        $response->assertRedirect(route('bookings.confirmation', $booking->booking_code));
    }

    public function test_booking_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->post(route('bookings.store'), []);

        $response->assertSessionHasErrors([
            'customer_name',
            'customer_phone',
            'property_type_selection',
            'pickup_address',
            'pickup_area',
            'pickup_floor',
            'delivery_address',
            'delivery_area',
            'delivery_floor',
            'shifting_date',
            'vehicle_type',
            'labour_count',
        ]);
    }

    public function test_confirmation_page_displays_booking_details(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SHZ-TEST123',
            'customer_name' => 'মীর জাফরুল',
            'customer_phone' => '01911223344',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'বাড়ি ১',
            'pickup_area' => 'মিরপুর',
            'pickup_floor' => 1,
            'delivery_address' => 'বাড়ি ২',
            'delivery_area' => 'বনানী',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(3),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'estimated_cost' => 8500,
            'status' => 'pending',
        ]);

        $response = $this->get(route('bookings.confirmation', $booking->booking_code));

        $response->assertStatus(200);
        $response->assertSee('SHZ-TEST123');
        $response->assertSee('মীর জাফরুল');
        $response->assertSee('মিরপুর');
        $response->assertSee('বনানী');
    }

    public function test_booking_track_finds_booking_by_code_and_phone(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SHZ-TRK999',
            'customer_name' => 'হাসান মাহমুদ',
            'customer_phone' => '01712349999',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'উত্তরা সেক্টর ৭',
            'pickup_area' => 'উত্তরা',
            'pickup_floor' => 2,
            'delivery_address' => 'গুলশান ১',
            'delivery_area' => 'গুলশান',
            'delivery_floor' => 3,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 2,
            'status' => 'pending',
        ]);

        // Search by tracking code
        $resCode = $this->get(route('bookings.track', ['search' => 'SHZ-TRK999']));
        $resCode->assertStatus(200);
        $resCode->assertSee('SHZ-TRK999');
        $resCode->assertSee('হাসান মাহমুদ');

        // Search by phone number
        $resPhone = $this->get(route('bookings.track', ['search' => '01712349999']));
        $resPhone->assertStatus(200);
        $resPhone->assertSee('SHZ-TRK999');
        $resPhone->assertSee('হাসান মাহমুদ');
    }

    public function test_admin_can_view_bookings_list_and_filter(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SHZ-ADM001',
            'customer_name' => 'আরিফুল ইসলাম',
            'customer_phone' => '01799887766',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'শ্যামলী রোড ২',
            'pickup_area' => 'শ্যামলী',
            'pickup_floor' => 1,
            'delivery_address' => 'লালমাটিয়া ডি ব্লক',
            'delivery_area' => 'লালমাটিয়া',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(1),
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 2,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.shifting-bookings.index'));

        $response->assertStatus(200);
        $response->assertSee('SHZ-ADM001');
        $response->assertSee('আরিফুল ইসলাম');
    }

    public function test_admin_can_update_booking_status_and_driver_info(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SHZ-ADM002',
            'customer_name' => 'সাদিয়া ইসলাম',
            'customer_phone' => '01655443322',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'মোহাম্মদপুর টাউনহল',
            'pickup_area' => 'মোহাম্মদপুর',
            'pickup_floor' => 2,
            'delivery_address' => 'বারিধারা ডিপ্লোম্যাটিক জোন',
            'delivery_area' => 'বারিধারা',
            'delivery_floor' => 3,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 3,
            'estimated_cost' => 12000,
            'status' => 'pending',
        ]);

        $updateData = [
            'status' => 'confirmed',
            'final_cost' => 12500,
            'advance_amount' => 2000,
            'payment_status' => 'partial_advance',
            'payment_method' => 'bKash',
            'transaction_id' => 'TRX987654321',
            'assigned_supervisor_name' => 'কামাল হোসেন',
            'assigned_supervisor_phone' => '01711000001',
            'driver_name' => 'রফিক উদ্দিন',
            'driver_phone' => '01711000002',
            'driver_truck_number' => 'ঢাকা মেট্রো-ন ১১-২২৩৩',
            'admin_notes' => 'ক্লায়েন্টের সাথে ফোনে কথা বলে ২০০০ টাকা অগ্রিম নেওয়া হয়েছে।',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.shifting-bookings.update', $booking), $updateData);

        $response->assertRedirect(route('admin.shifting-bookings.show', $booking));

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals(12500, $booking->final_cost);
        $this->assertEquals(2000, $booking->advance_amount);
        $this->assertEquals(10500, $booking->due_amount);
        $this->assertEquals('partial_advance', $booking->payment_status);
        $this->assertEquals('কামাল হোসেন', $booking->assigned_supervisor_name);
        $this->assertEquals('রফিক উদ্দিন', $booking->driver_name);
        $this->assertEquals('ঢাকা মেট্রো-ন ১১-২২৩৩', $booking->driver_truck_number);
    }

    public function test_admin_can_view_print_challan(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SHZ-CHALLAN',
            'customer_name' => 'ফরহাদ রেজা',
            'customer_phone' => '01511223344',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'মীরপুর ১',
            'pickup_area' => 'মিরপুর',
            'pickup_floor' => 1,
            'delivery_address' => 'উত্তরা ৩',
            'delivery_area' => 'উত্তরা',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'final_cost' => 9500,
            'advance_amount' => 1500,
            'due_amount' => 8000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.shifting-bookings.print-challan', $booking));

        $response->assertStatus(200);
        $response->assertSee('SHZ-CHALLAN');
        $response->assertSee('ফরহাদ রেজা');
        $response->assertSee('শিফটিং চালান ও রিসিট');
    }
}
