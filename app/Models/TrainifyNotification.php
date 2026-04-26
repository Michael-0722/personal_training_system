<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainifyNotification extends Model
{
    protected $table = 'trainify_notifications';

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'is_read', 'data',
    ];

    protected function casts(): array
    {
        return ['is_read' => 'boolean', 'data' => 'array'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
