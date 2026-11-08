<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shifting_booking_id')->constrained('shifting_bookings')->cascadeOnDelete();
            $table->string('quotation_number', 50)->unique();
            $table->json('line_items');
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('advance_required', 10, 2)->default(0.00);
            $table->text('terms_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('draft'); // draft, sent, approved, rejected
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shifting_booking_id')->constrained('shifting_bookings')->cascadeOnDelete();
            $table->string('invoice_number', 50)->unique();
            $table->date('invoice_date');
            $table->json('line_items');
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('advance_paid', 10, 2)->default(0.00);
            $table->decimal('due_balance', 10, 2)->default(0.00);
            $table->string('payment_status', 30)->default('unpaid'); // unpaid, partial_advance, paid_in_full
            $table->string('payment_method', 50)->nullable();
            $table->string('transaction_id', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_invoices');
        Schema::dropIfExists('booking_quotations');
    }
};

