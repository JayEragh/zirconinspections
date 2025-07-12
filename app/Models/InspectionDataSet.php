<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionDataSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
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
        'notes',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'inspection_time' => 'string',
        'product_gauge' => 'decimal:3',
        'water_gauge' => 'decimal:3',
        'temperature' => 'decimal:3',
        'has_roof' => 'boolean',
        'roof_weight' => 'decimal:3',
        'density' => 'decimal:3',
        'vcf' => 'decimal:3',
        'tov' => 'decimal:3',
        'water_volume' => 'decimal:3',
        'roof_volume' => 'decimal:3',
        'gov' => 'decimal:3',
        'gsv' => 'decimal:3',
        'mt_air' => 'decimal:3',
    ];

    /**
     * Get the report that owns the data set.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
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
