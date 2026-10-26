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
        Schema::create('shifting_booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shifting_booking_id')->constrained('shifting_bookings')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('service_name');
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifting_booking_services');
    }
};
