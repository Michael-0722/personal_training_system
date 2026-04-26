<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id', 'trainer_id', 'session_type_id',
        'booking_date', 'booking_time', 'duration_minutes',
        'amount', 'status', 'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'amount' => 'float',
        ];
    }

    // ── Relationships ──────────────────────
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function sessionType()
    {
        return $this->belongsTo(SessionType::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // ── Helpers ────────────────────────────
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
