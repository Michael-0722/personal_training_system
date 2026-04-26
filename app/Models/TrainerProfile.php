<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'specializations', 'tags',
        'rating', 'review_count', 'approval_status',
        'sessions_completed', 'total_earnings', 'hourly_rate',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'specializations' => 'array',
            'tags' => 'array',
            'rating' => 'float',
        ];
    }

    protected function getSpecializationsAttribute($value): array
    {
        return $value ? (array) json_decode($value, true) : [];
    }

    protected function getTagsAttribute($value): array
    {
        return $value ? (array) json_decode($value, true) : [];
    }

    // ── Relationships ──────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sessionTypes()
    {
        return $this->hasMany(SessionType::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    // ── Helpers ────────────────────────────
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval_status === 'rejected';
    }
}
