<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shifting_booking_id')->constrained('shifting_bookings')->cascadeOnDelete();
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('contact_type', 30)->default('phone_call'); // phone_call, whatsapp, site_visit, email
            $table->text('notes');
            $table->dateTime('next_follow_up_date')->nullable();
            $table->string('outcome', 50)->nullable(); // interested, quote_requested, bargaining, declined, confirmed
            $table->timestamps();
        });

        Schema::create('booking_support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shifting_booking_id')->constrained('shifting_bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('ticket_number', 50)->unique();
            $table->string('subject');
            $table->string('category', 30)->default('complaint'); // complaint, feedback, query, reschedule
            $table->string('priority', 20)->default('normal'); // normal, high, urgent
            $table->string('status', 20)->default('open'); // open, in_review, resolved, closed
            $table->json('messages'); // array of [{sender: 'customer'|'admin', sender_name: '...', message: '...', timestamp: '...'}]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_support_tickets');
        Schema::dropIfExists('booking_follow_ups');
    }
};

