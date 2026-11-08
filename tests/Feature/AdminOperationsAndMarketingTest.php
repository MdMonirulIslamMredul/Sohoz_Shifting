<?php

namespace Tests\Feature;

use App\Models\BookingFollowUp;
use App\Models\BookingInvoice;
use App\Models\BookingQuotation;
use App\Models\BookingSupportTicket;
use App\Models\Coupon;
use App\Models\PropertyType;
use App\Models\Setting;
use App\Models\ShiftingBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOperationsAndMarketingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected PropertyType $propertyType;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create(['site_name' => 'Sohoz Shifting']);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'Web_Settings']);

        $this->admin = User::create([
            'name' => 'Admin Manager',
            'phone' => '01700000001',
            'email' => 'admin_mgr@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);
        $this->admin->givePermissionTo('Web_Settings');

        $this->customer = User::create([
            'name' => 'Test Customer',
            'phone' => '01700000002',
            'email' => 'customer_test@test.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
        ]);

        $this->propertyType = PropertyType::create([
            'name' => 'Standard Flat',
            'name_bn' => 'স্ট্যান্ডার্ড ফ্ল্যাট',
            'base_price' => 6000,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_poll_new_booking_notifications(): void
    {
        ShiftingBooking::create([
            'booking_code' => 'SH-NEW-NOTIF',
            'customer_name' => 'Notification Lead',
            'customer_phone' => '01899999999',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Mirpur',
            'pickup_area' => 'Mirpur',
            'pickup_floor' => 1,
            'delivery_address' => 'Gulshan',
            'delivery_area' => 'Gulshan',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'new_request',
        ]);

        $res = $this->actingAs($this->admin)->getJson(route('admin.shifting-bookings.check-new'));
        $res->assertStatus(200);
        $res->assertJson([
            'new_count' => 1,
            'latest_code' => 'SH-NEW-NOTIF',
            'customer_name' => 'Notification Lead',
        ]);
    }

    public function test_admin_can_advance_booking_through_client_statuses(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SH-CYCLE-01',
            'customer_name' => 'Lifecycle Lead',
            'customer_phone' => '01711122233',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'Origin',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'Dest',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(3),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'new_request',
        ]);

        // 1. Mark as contacted
        $res = $this->actingAs($this->admin)->put(route('admin.shifting-bookings.update', $booking), [
            'status' => 'contacted',
            'payment_status' => 'unpaid',
        ]);
        $res->assertRedirect();
        $booking->refresh();
        $this->assertEquals('contacted', $booking->status);

        // 2. Mark as site_visit_scheduled
        $this->actingAs($this->admin)->put(route('admin.shifting-bookings.update', $booking), [
            'status' => 'site_visit_scheduled',
            'site_visit_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'payment_status' => 'unpaid',
        ]);
        $booking->refresh();
        $this->assertEquals('site_visit_scheduled', $booking->status);
        $this->assertNotNull($booking->site_visit_date);

        // 3. Mark as in_progress with driver and truck assignment
        $this->actingAs($this->admin)->put(route('admin.shifting-bookings.update', $booking), [
            'status' => 'in_progress',
            'payment_status' => 'partial_advance',
            'advance_amount' => 2000,
            'final_cost' => 8000,
            'assigned_supervisor_name' => 'Supervisor Alam',
            'driver_name' => 'Driver Karim',
            'driver_truck_number' => 'DHK-METRO-1122',
        ]);
        $booking->refresh();
        $this->assertEquals('in_progress', $booking->status);
        $this->assertEquals('Supervisor Alam', $booking->assigned_supervisor_name);
        $this->assertEquals('DHK-METRO-1122', $booking->driver_truck_number);
        $this->assertEquals(6000, $booking->due_amount);

        // 4. Mark as completed
        $this->actingAs($this->admin)->put(route('admin.shifting-bookings.update', $booking), [
            'status' => 'completed',
            'payment_status' => 'paid_in_full',
            'advance_amount' => 8000,
            'final_cost' => 8000,
        ]);
        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
        $this->assertEquals(0, $booking->due_amount);
    }

    public function test_admin_can_generate_quotation_and_invoice(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SH-DOCS-01',
            'customer_name' => 'Billing Customer',
            'customer_phone' => '01733333333',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'Origin',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'Dest',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'assessment_required',
        ]);

        // 1. Generate Quotation
        $quoteRes = $this->actingAs($this->admin)->post(route('admin.shifting-bookings.store-quotation', $booking), [
            'items' => [
                ['description' => 'Shifting Base Package', 'quantity' => 1, 'rate' => 7000],
                ['description' => 'Bubble Wrap Protection', 'quantity' => 3, 'rate' => 500],
            ],
            'discount_amount' => 500,
            'advance_required' => 1500,
            'notes' => 'Valid for 7 days',
        ]);
        $quoteRes->assertRedirect();

        $booking->refresh();
        $this->assertEquals('quotation_sent', $booking->status);
        $this->assertEquals(8000, $booking->final_cost);

        $quotation = BookingQuotation::where('shifting_booking_id', $booking->id)->first();
        $this->assertNotNull($quotation);
        $this->assertEquals(8500, $quotation->subtotal);
        $this->assertEquals(8000, $quotation->total_amount);

        // 2. Generate Official Invoice
        $invoiceRes = $this->actingAs($this->admin)->post(route('admin.shifting-bookings.store-invoice', $booking), [
            'items' => [
                ['description' => 'Shifting Final Bill', 'quantity' => 1, 'rate' => 8000],
            ],
            'discount_amount' => 0,
            'advance_paid' => 3000,
            'payment_status' => 'partial_advance',
            'payment_method' => 'bKash',
            'transaction_id' => 'TRX12345678',
            'notes' => 'Received 3000 BDT via bKash',
        ]);
        $invoiceRes->assertRedirect();

        $booking->refresh();
        $invoice = BookingInvoice::where('shifting_booking_id', $booking->id)->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(8000, $invoice->total_amount);
        $this->assertEquals(3000, $invoice->advance_paid);
        $this->assertEquals(5000, $invoice->due_balance);
        $this->assertEquals('partial_advance', $booking->payment_status);
        $this->assertEquals('TRX12345678', $booking->transaction_id);
    }

    public function test_admin_can_log_crm_customer_follow_up(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SH-CRM-01',
            'customer_name' => 'CRM Lead',
            'customer_phone' => '01744444444',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'Origin',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'Dest',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'contacted',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.shifting-bookings.store-follow-up', $booking), [
            'contact_type' => 'phone_call',
            'notes' => 'Customer requested a callback tomorrow at 3 PM to finalize price.',
            'next_follow_up_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'outcome' => 'bargaining',
        ]);
        $res->assertRedirect();

        $this->assertDatabaseHas('booking_follow_ups', [
            'shifting_booking_id' => $booking->id,
            'admin_user_id' => $this->admin->id,
            'contact_type' => 'phone_call',
            'outcome' => 'bargaining',
        ]);
    }

    public function test_admin_can_approve_reschedule_and_cancellation(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SH-REQ-01',
            'customer_name' => 'Reschedule Lead',
            'customer_phone' => '01755555555',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'Origin',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'Dest',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'reschedule_requested_date' => now()->addDays(7)->toDateString(),
            'reschedule_notes' => 'New key handover delayed',
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'confirmed',
        ]);

        // Approve reschedule
        $resApprove = $this->actingAs($this->admin)->post(route('admin.shifting-bookings.handle-request', $booking), [
            'action' => 'approve_reschedule',
        ]);
        $resApprove->assertRedirect();

        $booking->refresh();
        $this->assertEquals(now()->addDays(7)->toDateString(), $booking->shifting_date->toDateString());
        $this->assertNull($booking->reschedule_requested_date);

        // Approve cancel
        $resCancel = $this->actingAs($this->admin)->post(route('admin.shifting-bookings.handle-request', $booking), [
            'action' => 'approve_cancel',
        ]);
        $resCancel->assertRedirect();

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
    }

    public function test_admin_coupon_crud(): void
    {
        // 1. Create Coupon
        $storeRes = $this->actingAs($this->admin)->post(route('admin.coupons.store'), [
            'code' => 'WINTER2026',
            'title' => 'Winter Special',
            'discount_type' => 'percentage',
            'discount_value' => 15,
            'min_booking_amount' => 5000,
            'max_discount' => 1200,
            'is_active' => true,
        ]);
        $storeRes->assertRedirect(route('admin.coupons.index'));

        $coupon = Coupon::where('code', 'WINTER2026')->first();
        $this->assertNotNull($coupon);
        $this->assertEquals(15, $coupon->discount_value);

        // 2. Edit / Update Coupon
        $updateRes = $this->actingAs($this->admin)->put(route('admin.coupons.update', $coupon), [
            'code' => 'WINTER2026',
            'title' => 'Winter Special Updated',
            'discount_type' => 'fixed',
            'discount_value' => 800,
            'min_booking_amount' => 6000,
            'is_active' => false,
        ]);
        $updateRes->assertRedirect(route('admin.coupons.index'));

        $coupon->refresh();
        $this->assertEquals('fixed', $coupon->discount_type);
        $this->assertEquals(800, $coupon->discount_value);
        $this->assertFalse($coupon->is_active);

        // 3. Delete Coupon
        $delRes = $this->actingAs($this->admin)->delete(route('admin.coupons.destroy', $coupon));
        $delRes->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    public function test_admin_can_manage_support_tickets(): void
    {
        $booking = ShiftingBooking::create([
            'booking_code' => 'SH-TCK-MGR',
            'customer_name' => 'Complainant',
            'customer_phone' => '01766666666',
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'Origin',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'Dest',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'confirmed',
        ]);

        $ticket = BookingSupportTicket::create([
            'shifting_booking_id' => $booking->id,
            'user_id' => $this->customer->id,
            'ticket_number' => 'TCK-2026-TEST1',
            'subject' => 'AC installation question',
            'category' => 'query',
            'priority' => 'normal',
            'status' => 'open',
            'messages' => [
                ['sender' => 'customer', 'sender_name' => 'Test Customer', 'message' => 'Do you bring extra copper pipes?', 'timestamp' => now()->toIso8601String()],
            ],
        ]);

        // 1. View ticket
        $resShow = $this->actingAs($this->admin)->get(route('admin.tickets.show', $ticket));
        $resShow->assertStatus(200);
        $resShow->assertSee('AC installation question');
        $resShow->assertSee('copper pipes');

        // 2. Admin reply
        $resReply = $this->actingAs($this->admin)->post(route('admin.tickets.reply', $ticket), [
            'message' => 'Yes, our technicians carry extra copper pipes charged at standard rates.',
        ]);
        $resReply->assertRedirect();

        $ticket->refresh();
        $this->assertEquals('in_review', $ticket->status);
        $this->assertCount(2, $ticket->messages);

        // 3. Mark ticket resolved
        $resStatus = $this->actingAs($this->admin)->patch(route('admin.tickets.update-status', $ticket), [
            'status' => 'resolved',
        ]);
        $resStatus->assertRedirect();
        $ticket->refresh();
        $this->assertEquals('resolved', $ticket->status);
    }

    public function test_admin_can_update_marketing_and_pixel_settings(): void
    {
        $res = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'site_name' => 'Sohoz Shifting BD',
            'meta_pixel_id' => '123456789012345',
            'ga4_measurement_id' => 'G-ABC1234567',
            'whatsapp_tracking_number' => '8801700123456',
            'enable_marketing_tracking' => '1',
        ]);

        $res->assertRedirect();

        $setting = Setting::first();
        $this->assertEquals('123456789012345', $setting->meta_pixel_id);
        $this->assertEquals('G-ABC1234567', $setting->ga4_measurement_id);
        $this->assertEquals('8801700123456', $setting->whatsapp_tracking_number);
        $this->assertTrue($setting->enable_marketing_tracking);

        // Verify pixel and ga4 tags appear in frontend HTML
        $homeRes = $this->get(route('home'));
        $homeRes->assertStatus(200);
        $homeRes->assertSee('123456789012345');
        $homeRes->assertSee('G-ABC1234567');
    }
}
