<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'shifting_booking_id',
        'user_id',
        'ticket_number',
        'subject',
        'category',
        'priority',
        'status',
        'messages',
    ];

    protected $casts = [
        'messages' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(ShiftingBooking::class, 'shifting_booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateTicketNumber(): string
    {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -5));
        $code = "TCK-{$year}-{$random}";

        while (static::where('ticket_number', $code)->exists()) {
            $random = strtoupper(substr(uniqid(), -5));
            $code = "TCK-{$year}-{$random}";
        }

        return $code;
    }
}

