<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'client_id',
        'inspector_id',
        'depot',
        'product',
        'quantity_gsv',
        'quantity_mt',
        'outturn_file',
        'quality_certificate_file',
        'tank_numbers',
        'service_type',
        'specific_instructions',
        'status',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'quantity_gsv' => 'decimal:2',
        'quantity_mt' => 'decimal:3',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the client that owns the service request.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the inspector assigned to this service request.
     */
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    /**
     * Get the reports for this service request.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the invoice for this service request.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Generate a unique service ID.
     */
    public static function generateServiceId()
    {
        do {
            $serviceId = 'SRV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('service_id', $serviceId)->exists());

        return $serviceId;
    }
}
