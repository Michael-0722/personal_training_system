<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['trainer_profile_id', 'day_of_week', 'start_time', 'end_time'];

    const DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Unknown';
    }

    public function trainerProfile()
    {
        return $this->belongsTo(TrainerProfile::class);
    }
}
