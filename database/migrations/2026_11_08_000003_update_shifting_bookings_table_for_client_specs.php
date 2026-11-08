<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shifting_bookings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->dateTime('site_visit_date')->nullable()->after('status');
            $table->date('reschedule_requested_date')->nullable()->after('site_visit_date');
            $table->text('reschedule_notes')->nullable()->after('reschedule_requested_date');
            $table->text('cancellation_reason')->nullable()->after('reschedule_notes');
            $table->json('special_items_json')->nullable()->after('custom_service_requests');
            $table->foreignId('coupon_id')->nullable()->after('labour_count')->constrained('coupons')->nullOnDelete();
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('coupon_id');
            $table->string('utm_source')->nullable()->after('admin_notes');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');
            $table->string('referrer_url')->nullable()->after('utm_content');
        });

        // Migrate any existing 'pending' to 'new_request'
        DB::table('shifting_bookings')->where('status', 'pending')->update(['status' => 'new_request']);
    }

    public function down(): void
    {
        Schema::table('shifting_bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
                'user_id',
                'site_visit_date',
                'reschedule_requested_date',
                'reschedule_notes',
                'cancellation_reason',
                'special_items_json',
                'coupon_id',
                'discount_amount',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'referrer_url',
            ]);
        });
    }
};

