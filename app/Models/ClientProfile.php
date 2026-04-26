<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
    protected $fillable = ['user_id', 'total_spent'];

    protected function casts(): array
    {
        return ['total_spent' => 'float'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
