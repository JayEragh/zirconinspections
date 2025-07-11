<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the client profile for this user.
     */
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get the inspector profile for this user.
     */
    public function inspector()
    {
        return $this->hasOne(Inspector::class);
    }

    /**
     * Check if user is a client.
     */
    public function isClient()
    {
        return $this->role === 'client';
    }

    /**
     * Check if user is an inspector.
     */
    public function isInspector()
    {
        return $this->role === 'inspector';
    }

    /**
     * Check if user is operations staff.
     */
    public function isOperations()
    {
        return $this->role === 'operations';
    }
}
