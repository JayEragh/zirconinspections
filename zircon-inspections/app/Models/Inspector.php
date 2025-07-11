<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'phone',
        'address',
        'certification_number',
        'certification_expiry',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'certification_expiry' => 'date',
    ];

    /**
     * Get the user that owns the inspector.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service requests assigned to this inspector.
     */
    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get the reports created by this inspector.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
