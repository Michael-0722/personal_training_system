<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id', 'trainer_id', 'gross_amount',
        'commission_rate', 'commission_amount',
        'trainer_payout', 'payout_status', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'float',
            'commission_rate' => 'float',
            'commission_amount' => 'float',
            'trainer_payout' => 'float',
            'paid_at' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
