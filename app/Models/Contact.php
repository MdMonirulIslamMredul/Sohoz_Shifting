<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'admin_notes',
        'read_at',
        'is_connected',
        'connected_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'is_connected' => 'boolean',
        'connected_at' => 'datetime',
    ];

    /**
     * Determine if message has been opened/read.
     */
    public function getIsReadAttribute(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Mark message as read/opened.
     */
    public function markAsRead(): bool
    {
        if (is_null($this->read_at)) {
            return $this->update(['read_at' => now()]);
        }
        return true;
    }

    /**
     * Mark message as unread.
     */
    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

    /**
     * Toggle read/unread status.
     */
    public function toggleRead(): bool
    {
        return $this->update([
            'read_at' => $this->is_read ? null : now(),
        ]);
    }

    /**
     * Toggle connected/not connected status.
     */
    public function toggleConnected(): bool
    {
        $newStatus = !$this->is_connected;
        return $this->update([
            'is_connected' => $newStatus,
            'connected_at' => $newStatus ? now() : null,
        ]);
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read messages.
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for connected inquiries.
     */
    public function scopeConnected(Builder $query): Builder
    {
        return $query->where('is_connected', true);
    }

    /**
     * Scope for not connected inquiries.
     */
    public function scopeNotConnected(Builder $query): Builder
    {
        return $query->where('is_connected', false);
    }
}

