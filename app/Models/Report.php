<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'inspector_id',
        'client_id',
        'title',
        'content',
        'findings',
        'recommendations',
        'inspection_date',
        'inspection_time',
        'tank_number',
        'product_gauge',
        'water_gauge',
        'temperature',
        'has_roof',
        'roof_weight',
        'density',
        'vcf',
        'tov',
        'water_volume',
        'roof_volume',
        'gov',
        'gsv',
        'mt_air',
        'supporting_file',
        'status',
        'submitted_at',
        'approved_at',
        'sent_to_client_at',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'inspection_time' => 'datetime',
        'product_gauge' => 'decimal:2',
        'water_gauge' => 'decimal:2',
        'temperature' => 'decimal:2',
        'has_roof' => 'boolean',
        'roof_weight' => 'decimal:2',
        'density' => 'decimal:4',
        'vcf' => 'decimal:4',
        'tov' => 'decimal:2',
        'water_volume' => 'decimal:2',
        'roof_volume' => 'decimal:2',
        'gov' => 'decimal:2',
        'gsv' => 'decimal:2',
        'mt_air' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'sent_to_client_at' => 'datetime',
    ];

    /**
     * Get the service request that owns the report.
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the inspector that created the report.
     */
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    /**
     * Get the client that owns the report.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Calculate roof volume based on roof weight, density, and VCF.
     */
    public function calculateRoofVolume()
    {
        if ($this->has_roof && $this->roof_weight && $this->density && $this->vcf) {
            return $this->roof_weight / ($this->density * $this->vcf);
        }
        return 0;
    }

    /**
     * Calculate GOV (Gross Observed Volume).
     */
    public function calculateGOV()
    {
        return $this->tov - $this->water_volume - $this->roof_volume;
    }

    /**
     * Calculate GSV (Gross Standard Volume).
     */
    public function calculateGSV()
    {
        return $this->gov * $this->vcf;
    }

    /**
     * Calculate MT Air.
     */
    public function calculateMTAir()
    {
        return $this->gsv * ($this->density - 0.0011);
    }
}
