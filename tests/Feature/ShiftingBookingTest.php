<?php

namespace Tests\Feature;

use App\Models\BookingInvoice;
use App\Models\BookingQuotation;
use App\Models\BookingSupportTicket;
use App\Models\Coupon;
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
    protected User $customer;
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
            'phone' => '01700000000',
            'email' => 'admin_shifting@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);

        $this->customer = User::create([
            'name' => 'তানভীর আহমেদ',
            'phone' => '01700123456',
            'email' => 'tanvir@gmail.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
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

    public function test_guest_is_redirected_to_login_when_accessing_booking(): void
    {
        $response = $this->get(route('bookings.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_login_using_phone_or_email(): void
    {
        // 1. Login with Phone
        $resPhone = $this->post(route('login.submit'), [
            'login' => '01700123456',
            'password' => 'password123',
        ]);
        $resPhone->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->customer);

        auth()->logout();

        // 2. Login with Email
        $resEmail = $this->post(route('login.submit'), [
            'login' => 'tanvir@gmail.com',
            'password' => 'password123',
        ]);
        $resEmail->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->customer);
    }

    public function test_authenticated_customer_can_view_booking_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('bookings.create'));

        $response->assertStatus(200);
        $response->assertSee('২ বেডরুম');
        $response->assertSee('এসি খোলা ও ফিটিং');
        $response->assertSee('বিশেষ বা ভারী মালামালের তালিকা');
    }

    public function test_customer_can_submit_booking_with_special_items_and_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'DISCOUNT500',
            'discount_type' => 'fixed',
            'discount_value' => 500,
            'min_booking_amount' => 5000,
            'is_active' => true,
        ]);

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
            'special_items' => ['স্প্লিট এসি (Split AC)', 'হেভি লোহার সিন্দুক / ভল্ট (Locker/Safe)'],
            'coupon_code' => 'DISCOUNT500',
            'selected_services' => [$this->service1->id, $this->service2->id],
            'custom_service_requests' => 'কাঁচের আসবাবপত্র রয়েছে',
            'special_notes' => 'সাবধানে লোড করবেন',
        ];

        $response = $this->actingAs($this->customer)->post(route('bookings.store'), $postData);

        $this->assertDatabaseHas('shifting_bookings', [
            'user_id' => $this->customer->id,
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01700123456',
            'property_type_id' => $this->propertyType->id,
            'status' => 'new_request',
            'discount_amount' => 500,
        ]);

        $booking = ShiftingBooking::where('customer_phone', '01700123456')->first();
        $this->assertNotNull($booking);
        $this->assertStringStartsWith('SH-', $booking->booking_code);
        $this->assertCount(2, $booking->bookingServices);
        $this->assertCount(2, $booking->special_items_json);

        $response->assertRedirect(route('bookings.confirmation', $booking->booking_code));
    }

    public function test_customer_can_view_dashboard_and_manage_booking(): void
    {
        $booking = ShiftingBooking::create([
            'user_id' => $this->customer->id,
            'booking_code' => 'SH-TEST-001',
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01700123456',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'মিরপুর',
            'pickup_area' => 'মিরপুর ১০',
            'pickup_floor' => 1,
            'delivery_address' => 'ধানমন্ডি',
            'delivery_area' => 'ধানমন্ডি',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(3),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'estimated_cost' => 8500,
            'status' => 'new_request',
        ]);

        $res = $this->actingAs($this->customer)->get(route('dashboard'));
        $res->assertStatus(200);
        $res->assertSee('SH-TEST-001');
        $res->assertSee('মিরপুর ১০');

        // Customer can cancel newly placed booking
        $cancelRes = $this->actingAs($this->customer)->post(route('user.bookings.cancel', $booking), [
            'cancellation_reason' => 'Changed my plan',
        ]);
        $cancelRes->assertRedirect();
        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    public function test_admin_can_generate_quotation_and_customer_can_approve(): void
    {
        $booking = ShiftingBooking::create([
            'user_id' => $this->customer->id,
            'booking_code' => 'SH-QUOTE-001',
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01700123456',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'উত্তরা',
            'pickup_area' => 'উত্তরা',
            'pickup_floor' => 1,
            'delivery_address' => 'গুলশান',
            'delivery_area' => 'গুলশান',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'covered_9ft',
            'labour_count' => 2,
            'estimated_cost' => 10000,
            'status' => 'site_visit_scheduled',
        ]);

        // Admin generates quotation
        $quotationData = [
            'items' => [
                ['description' => 'শিফটিং প্যাকেজ', 'quantity' => 1, 'rate' => 10000],
                ['description' => 'প্যাকিং বাবল শিট', 'quantity' => 2, 'rate' => 1000],
            ],
            'discount_amount' => 500,
            'advance_required' => 2000,
            'notes' => 'সকল প্যাকিং ম্যাটেরিয়াল অন্তর্ভুক্ত',
        ];

        $resAdmin = $this->actingAs($this->admin)->post(route('admin.shifting-bookings.store-quotation', $booking), $quotationData);
        $resAdmin->assertRedirect();

        $booking->refresh();
        $this->assertEquals('quotation_sent', $booking->status);
        $this->assertEquals(11500, $booking->final_cost);

        $quotation = BookingQuotation::where('shifting_booking_id', $booking->id)->first();
        $this->assertNotNull($quotation);
        $this->assertEquals('sent', $quotation->status);

        // Customer views quotation
        $resQuote = $this->actingAs($this->customer)->get(route('user.quotations.show', $booking));
        $resQuote->assertStatus(200);
        $resQuote->assertSee('শিফটিং কোটেশন');
        $resQuote->assertSee('11,500');

        // Customer approves quotation
        $resApprove = $this->actingAs($this->customer)->post(route('user.quotations.approve', $booking));
        $resApprove->assertRedirect();

        $booking->refresh();
        $quotation->refresh();
        $this->assertEquals('customer_approved', $booking->status);
        $this->assertEquals('approved', $quotation->status);
    }

    public function test_customer_can_create_and_admin_can_reply_support_ticket(): void
    {
        $booking = ShiftingBooking::create([
            'user_id' => $this->customer->id,
            'booking_code' => 'SH-TCK-001',
            'customer_name' => 'তানভীর আহমেদ',
            'customer_phone' => '01700123456',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'ধানমন্ডি',
            'pickup_area' => 'ধানমন্ডি',
            'pickup_floor' => 1,
            'delivery_address' => 'বনানী',
            'delivery_area' => 'বনানী',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'confirmed',
        ]);

        // Customer opens ticket
        $ticketData = [
            'subject' => 'Need additional carton boxes',
            'category' => 'query',
            'priority' => 'normal',
            'message' => 'Can you please bring 5 extra master carton boxes on moving day?',
        ];

        $resTicket = $this->actingAs($this->customer)->post(route('user.tickets.store', $booking), $ticketData);
        $resTicket->assertRedirect();

        $ticket = BookingSupportTicket::where('shifting_booking_id', $booking->id)->first();
        $this->assertNotNull($ticket);
        $this->assertEquals('open', $ticket->status);

        // Admin replies to ticket
        $resReply = $this->actingAs($this->admin)->post(route('admin.tickets.reply', $ticket), [
            'message' => 'Yes, our team will bring 5 extra cartons with them.',
        ]);
        $resReply->assertRedirect();

        $ticket->refresh();
        $this->assertEquals('in_review', $ticket->status);
        $this->assertCount(2, $ticket->messages);
    }

    public function test_admin_can_view_reports_and_campaigns(): void
    {
        $resReports = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $resReports->assertStatus(200);
        $resReports->assertSee('Operations &amp; Revenue Reports', false);

        $resCampaigns = $this->actingAs($this->admin)->get(route('admin.reports.campaigns'));
        $resCampaigns->assertStatus(200);
        $resCampaigns->assertSee('Campaign Performance Attribution');
    }
}
