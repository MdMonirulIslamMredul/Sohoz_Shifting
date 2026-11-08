<?php

namespace Tests\Feature;

use App\Models\BookingInvoice;
use App\Models\BookingQuotation;
use App\Models\BookingSupportTicket;
use App\Models\PropertyType;
use App\Models\Setting;
use App\Models\ShiftingBooking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerAuthAndPortalTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer1;
    protected User $customer2;
    protected PropertyType $propertyType;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create(['site_name' => 'Sohoz Shifting']);

        $this->customer1 = User::create([
            'name' => 'Customer One',
            'phone' => '01711111111',
            'email' => 'customer1@test.com',
            'password' => bcrypt('secret123'),
            'is_admin' => false,
        ]);

        $this->customer2 = User::create([
            'name' => 'Customer Two',
            'phone' => '01722222222',
            'email' => 'customer2@test.com',
            'password' => bcrypt('secret123'),
            'is_admin' => false,
        ]);

        $this->propertyType = PropertyType::create([
            'name' => 'Flat',
            'name_bn' => 'ফ্ল্যাট',
            'base_price' => 5000,
            'is_active' => true,
        ]);
    }

    public function test_customer_registration_with_phone_and_email(): void
    {
        $res = $this->post(route('register.submit'), [
            'name' => 'New User',
            'phone' => '01833333333',
            'email' => 'newuser@test.com',
            'password' => 'pass1234',
            'password_confirmation' => 'pass1234',
        ]);

        $res->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'phone' => '01833333333',
            'email' => 'newuser@test.com',
            'is_admin' => false,
        ]);
        $this->assertAuthenticated();
    }

    public function test_customer_registration_fails_on_duplicate_phone_or_email(): void
    {
        $res = $this->post(route('register.submit'), [
            'name' => 'Duplicate User',
            'phone' => '01711111111', // already used
            'email' => 'customer1@test.com', // already used
            'password' => 'pass1234',
            'password_confirmation' => 'pass1234',
        ]);

        $res->assertSessionHasErrors(['phone', 'email']);
    }

    public function test_customer_can_login_with_phone_number(): void
    {
        $res = $this->post(route('login.submit'), [
            'login' => '01711111111',
            'password' => 'secret123',
        ]);

        $res->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->customer1);
    }

    public function test_customer_can_login_with_email_address(): void
    {
        $res = $this->post(route('login.submit'), [
            'login' => 'customer1@test.com',
            'password' => 'secret123',
        ]);

        $res->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->customer1);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $res = $this->post(route('login.submit'), [
            'login' => '01711111111',
            'password' => 'wrongpassword',
        ]);

        $res->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_guest_cannot_access_customer_dashboard(): void
    {
        $res = $this->get(route('dashboard'));
        $res->assertRedirect(route('login'));
    }

    public function test_customer_dashboard_renders_active_and_past_bookings(): void
    {
        $activeBooking = ShiftingBooking::create([
            'user_id' => $this->customer1->id,
            'booking_code' => 'SH-ACTIVE-1',
            'customer_name' => $this->customer1->name,
            'customer_phone' => $this->customer1->phone,
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Mirpur',
            'pickup_area' => 'Mirpur 10',
            'pickup_floor' => 1,
            'delivery_address' => 'Gulshan',
            'delivery_area' => 'Gulshan 2',
            'delivery_floor' => 2,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'estimated_cost' => 6000,
            'status' => 'new_request',
        ]);

        $pastBooking = ShiftingBooking::create([
            'user_id' => $this->customer1->id,
            'booking_code' => 'SH-PAST-1',
            'customer_name' => $this->customer1->name,
            'customer_phone' => $this->customer1->phone,
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Uttara',
            'pickup_area' => 'Uttara',
            'pickup_floor' => 1,
            'delivery_address' => 'Dhanmondi',
            'delivery_area' => 'Dhanmondi',
            'delivery_floor' => 1,
            'shifting_date' => now()->subDays(5),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'estimated_cost' => 5500,
            'status' => 'completed',
        ]);

        $res = $this->actingAs($this->customer1)->get(route('dashboard'));
        $res->assertStatus(200);
        $res->assertSee('SH-ACTIVE-1');
        $res->assertSee('SH-PAST-1');
        $res->assertSee('Mirpur 10');
        $res->assertSee('Gulshan 2');
    }

    public function test_customer_can_request_reschedule(): void
    {
        $booking = ShiftingBooking::create([
            'user_id' => $this->customer1->id,
            'booking_code' => 'SH-RESCHED',
            'customer_name' => $this->customer1->name,
            'customer_phone' => $this->customer1->phone,
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Address',
            'pickup_area' => 'Area',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'DestArea',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 1,
            'status' => 'confirmed',
        ]);

        $newDate = now()->addDays(5)->format('Y-m-d');
        $res = $this->actingAs($this->customer1)->post(route('user.bookings.reschedule', $booking), [
            'reschedule_requested_date' => $newDate,
            'reschedule_notes' => 'Flat painting not finished yet',
        ]);

        $res->assertRedirect();
        $booking->refresh();
        $this->assertEquals($newDate, $booking->reschedule_requested_date->format('Y-m-d'));
        $this->assertEquals('Flat painting not finished yet', $booking->reschedule_notes);
    }

    public function test_customer_can_view_and_approve_quotation(): void
    {
        $booking = ShiftingBooking::create([
            'user_id' => $this->customer1->id,
            'booking_code' => 'SH-APPRV-Q',
            'customer_name' => $this->customer1->name,
            'customer_phone' => $this->customer1->phone,
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'OriginArea',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'DestArea',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'quotation_sent',
        ]);

        $quotation = BookingQuotation::create([
            'shifting_booking_id' => $booking->id,
            'quotation_number' => 'QT-2026-TEST1',
            'line_items' => [['description' => 'Moving Package', 'quantity' => 1, 'rate' => 8500]],
            'subtotal' => 8500,
            'total_amount' => 8500,
            'advance_required' => 1000,
            'status' => 'sent',
        ]);

        // Customer views quotation
        $viewRes = $this->actingAs($this->customer1)->get(route('user.quotations.show', $booking));
        $viewRes->assertStatus(200);
        $viewRes->assertSee('QT-2026-TEST1');
        $viewRes->assertSee('8,500');

        // Customer approves quotation
        $apprvRes = $this->actingAs($this->customer1)->post(route('user.quotations.approve', $booking));
        $apprvRes->assertRedirect();

        $booking->refresh();
        $quotation->refresh();
        $this->assertEquals('customer_approved', $booking->status);
        $this->assertEquals('approved', $quotation->status);
    }

    public function test_customer_can_view_invoice(): void
    {
        $booking = ShiftingBooking::create([
            'user_id' => $this->customer1->id,
            'booking_code' => 'SH-INV-TEST',
            'customer_name' => $this->customer1->name,
            'customer_phone' => $this->customer1->phone,
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Origin',
            'pickup_area' => 'OriginArea',
            'pickup_floor' => 1,
            'delivery_address' => 'Dest',
            'delivery_area' => 'DestArea',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 2,
            'status' => 'confirmed',
        ]);

        BookingInvoice::create([
            'shifting_booking_id' => $booking->id,
            'invoice_number' => 'INV-2026-TEST1',
            'invoice_date' => now()->toDateString(),
            'line_items' => [['description' => 'Full Move Bill', 'quantity' => 1, 'rate' => 9000]],
            'subtotal' => 9000,
            'total_amount' => 9000,
            'advance_paid' => 2000,
            'due_balance' => 7000,
            'payment_status' => 'partial_advance',
        ]);

        $res = $this->actingAs($this->customer1)->get(route('user.invoices.show', $booking));
        $res->assertStatus(200);
        $res->assertSee('INV-2026-TEST1');
        $res->assertSee('9,000');
        $res->assertSee('2,000');
        $res->assertSee('7,000');
    }

    public function test_customer_cannot_access_another_customers_booking_or_invoice(): void
    {
        $bookingOfCustomer2 = ShiftingBooking::create([
            'user_id' => $this->customer2->id,
            'booking_code' => 'SH-PRIVATE-2',
            'customer_name' => $this->customer2->name,
            'customer_phone' => $this->customer2->phone,
            'property_type_id' => $this->propertyType->id,
            'pickup_address' => 'Secret',
            'pickup_area' => 'SecretArea',
            'pickup_floor' => 1,
            'delivery_address' => 'SecretDest',
            'delivery_area' => 'SecretDestArea',
            'delivery_floor' => 1,
            'shifting_date' => now()->addDays(2),
            'vehicle_type' => 'pickup_7ft',
            'labour_count' => 1,
            'status' => 'confirmed',
        ]);

        // Customer 1 tries to view Customer 2's booking
        $res = $this->actingAs($this->customer1)->get(route('user.bookings.show', $bookingOfCustomer2));
        $res->assertStatus(403);

        // Customer 1 tries to cancel Customer 2's booking
        $resCancel = $this->actingAs($this->customer1)->post(route('user.bookings.cancel', $bookingOfCustomer2), [
            'cancellation_reason' => 'Hack cancel',
        ]);
        $resCancel->assertStatus(403);
    }
}

