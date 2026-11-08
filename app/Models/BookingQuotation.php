<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'shifting_booking_id',
        'quotation_number',
        'line_items',
        'subtotal',
        'discount_amount',
        'total_amount',
        'advance_required',
        'terms_conditions',
        'notes',
        'status',
        'sent_at',
        'approved_at',
    ];

    protected $casts = [
        'line_items' => 'array',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'advance_required' => 'decimal:2',
        'sent_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(ShiftingBooking::class, 'shifting_booking_id');
    }

    public static function generateQuotationNumber(): string
    {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -5));
        $code = "QT-{$year}-{$random}";

        while (static::where('quotation_number', $code)->exists()) {
            $random = strtoupper(substr(uniqid(), -5));
            $code = "QT-{$year}-{$random}";
        }

        return $code;
    }
}

