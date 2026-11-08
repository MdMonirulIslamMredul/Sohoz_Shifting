<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'shifting_booking_id',
        'admin_user_id',
        'contact_type',
        'notes',
        'next_follow_up_date',
        'outcome',
    ];

    protected $casts = [
        'next_follow_up_date' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(ShiftingBooking::class, 'shifting_booking_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}

