<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionType extends Model
{
    protected $fillable = [
        'trainer_profile_id', 'title', 'description',
        'format', 'delivery_mode', 'duration_minutes',
        'price', 'max_participants', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function trainerProfile()
    {
        return $this->belongsTo(TrainerProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
