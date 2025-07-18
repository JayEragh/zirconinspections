<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'ip_address', 'user_agent', 'logged_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
