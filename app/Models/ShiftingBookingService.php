<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftingBookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'shifting_booking_id',
        'service_id',
        'service_name',
        'unit_price',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(ShiftingBooking::class, 'shifting_booking_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
