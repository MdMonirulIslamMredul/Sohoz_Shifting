<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shifting_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 30)->unique();
            $table->string('customer_name');
            $table->string('customer_phone', 30);
            $table->string('customer_email')->nullable();
            $table->string('customer_whatsapp', 30)->nullable();

            // Dynamic Property Type reference or manual custom input
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->nullOnDelete();
            $table->string('custom_property_type')->nullable(); // User's manual custom input if not selecting a standard type

            // Pickup / Origin
            $table->text('pickup_address');
            $table->string('pickup_area');
            $table->integer('pickup_floor')->default(1);
            $table->boolean('pickup_has_lift')->default(false);

            // Delivery / Destination
            $table->text('delivery_address');
            $table->string('delivery_area');
            $table->integer('delivery_floor')->default(1);
            $table->boolean('delivery_has_lift')->default(false);

            // Schedule & Vehicles
            $table->date('shifting_date');
            $table->string('preferred_time_slot')->default('Morning (8 AM - 12 PM)');
            $table->string('vehicle_type')->default('pickup_7ft');
            $table->integer('labour_count')->default(2);

            // Financials
            $table->decimal('estimated_cost', 10, 2)->default(0.00);
            $table->decimal('final_cost', 10, 2)->nullable();
            $table->decimal('advance_amount', 10, 2)->default(0.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            $table->string('payment_status')->default('unpaid'); // unpaid, partial_advance, paid_in_full
            $table->string('payment_method')->nullable(); // bKash, Nagad, Cash
            $table->string('transaction_id')->nullable();

            // Manual custom services requested by customer
            $table->text('custom_service_requests')->nullable();

            // Operations & Dispatch Status
            $table->string('status')->default('pending'); // pending, contacted, confirmed, team_assigned, in_progress, completed, cancelled
            $table->string('assigned_supervisor_name')->nullable();
            $table->string('assigned_supervisor_phone')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('driver_truck_number')->nullable();

            $table->text('special_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifting_bookings');
    }
};
