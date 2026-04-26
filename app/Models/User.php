<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'full_name',
        'password',
        'role',
        'account_status',
        'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function trainerProfile()
    {
        return $this->hasOne(TrainerProfile::class);
    }

    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    public function bookingsAsClient()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function bookingsAsTrainer()
    {
        return $this->hasMany(Booking::class, 'trainer_id');
    }

    public function notifications()
    {
        return $this->hasMany(TrainifyNotification::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isActive(): bool
    {
        return $this->account_status === 'active';
    }
}
