<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'shifting_booking_id',
        'invoice_number',
        'invoice_date',
        'line_items',
        'subtotal',
        'discount_amount',
        'total_amount',
        'advance_paid',
        'due_balance',
        'payment_status',
        'payment_method',
        'transaction_id',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'line_items' => 'array',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'advance_paid' => 'decimal:2',
        'due_balance' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(ShiftingBooking::class, 'shifting_booking_id');
    }

    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -5));
        $code = "INV-{$year}-{$random}";

        while (static::where('invoice_number', $code)->exists()) {
            $random = strtoupper(substr(uniqid(), -5));
            $code = "INV-{$year}-{$random}";
        }

        return $code;
    }
}

