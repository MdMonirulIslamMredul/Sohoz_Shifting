<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftingBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_whatsapp',
        'property_type_id',
        'custom_property_type',
        'pickup_address',
        'pickup_area',
        'pickup_floor',
        'pickup_has_lift',
        'delivery_address',
        'delivery_area',
        'delivery_floor',
        'delivery_has_lift',
        'shifting_date',
        'preferred_time_slot',
        'vehicle_type',
        'labour_count',
        'estimated_cost',
        'final_cost',
        'advance_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'custom_service_requests',
        'status',
        'assigned_supervisor_name',
        'assigned_supervisor_phone',
        'driver_name',
        'driver_phone',
        'driver_truck_number',
        'special_notes',
        'admin_notes',
    ];

    protected $casts = [
        'shifting_date' => 'date',
        'pickup_has_lift' => 'boolean',
        'delivery_has_lift' => 'boolean',
        'pickup_floor' => 'integer',
        'delivery_floor' => 'integer',
        'labour_count' => 'integer',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function bookingServices()
    {
        return $this->hasMany(ShiftingBookingService::class, 'shifting_booking_id');
    }

    public function getDisplayPropertyTypeAttribute(): string
    {
        if ($this->propertyType) {
            return $this->propertyType->name_bn ?: $this->propertyType->name;
        }

        return $this->custom_property_type ?: 'অন্যান্য / কাস্টম';
    }

    public function getStatusLabelBnAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'নতুন রিকোয়েস্ট (Pending)',
            'contacted' => 'যোগাযোগ করা হয়েছে (Contacted)',
            'confirmed' => 'বুকিং নিশ্চিত (Confirmed)',
            'team_assigned' => 'টিম নিয়োজিত (Team Assigned)',
            'in_progress' => 'শিফটিং চলছে (In Progress)',
            'completed' => 'সম্পন্ন (Completed)',
            'cancelled' => 'বাতিল (Cancelled)',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge bg-warning text-dark',
            'contacted' => 'badge bg-info text-dark',
            'confirmed' => 'badge bg-primary',
            'team_assigned' => 'badge bg-indigo text-white',
            'in_progress' => 'badge bg-teal text-white',
            'completed' => 'badge bg-success',
            'cancelled' => 'badge bg-danger',
            default => 'badge bg-secondary',
        };
    }

    public function getPaymentStatusLabelBnAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'বাকি (Unpaid)',
            'partial_advance' => 'অগ্রিম পরিশোধিত (Partial Advance)',
            'paid_in_full' => 'সম্পূর্ণ পরিশোধিত (Paid in Full)',
            default => ucfirst($this->payment_status),
        };
    }

    public function getVehicleTypeLabelBnAttribute(): string
    {
        return match ($this->vehicle_type) {
            'pickup_7ft' => '৭ ফিট ছোট পিকআপ ভ্যান (Small Pickup 7ft)',
            'covered_9ft' => '৯ ফিট কাভার্ড ভ্যান (Covered Van 9ft)',
            'covered_14ft' => '১৪ ফিট বড় কাভার্ড ট্রাক (Covered Truck 14ft)',
            'customer_managed' => 'গ্রাহক নিজ দায়িত্বে ব্যবস্থা করবেন',
            default => $this->vehicle_type,
        };
    }

    public static function generateBookingCode(): string
    {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -5));
        $code = "SH-{$year}-{$random}";

        while (static::where('booking_code', $code)->exists()) {
            $random = strtoupper(substr(uniqid(), -5));
            $code = "SH-{$year}-{$random}";
        }

        return $code;
    }
}
