<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutturnDataSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'outturn_report_id',
        'tank_number',
        'data_type',
        'inspection_date',
        'inspection_time',
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
        'mt_vac',
        'notes',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'inspection_time' => 'datetime',
        'product_gauge' => 'decimal:3',
        'water_gauge' => 'decimal:3',
        'temperature' => 'decimal:2',
        'has_roof' => 'boolean',
        'roof_weight' => 'decimal:3',
        'density' => 'decimal:4',
        'vcf' => 'decimal:4',
        'tov' => 'decimal:3',
        'water_volume' => 'decimal:3',
        'roof_volume' => 'decimal:3',
        'gov' => 'decimal:3',
        'gsv' => 'decimal:3',
        'mt_air' => 'decimal:3',
        'mt_vac' => 'decimal:3',
    ];

    /**
     * Get the outturn report that owns this data set.
     */
    public function outturnReport()
    {
        return $this->belongsTo(OutturnReport::class);
    }

    /**
     * Calculate MT Vac based on other values.
     */
    public function calculateMtVac()
    {
        // MT Vac calculation: GSV × Density
        if ($this->gsv && $this->density) {
            $this->mt_vac = $this->gsv * $this->density;
        }
    }

    /**
     * Get formatted values.
     */
    public function getFormattedProductGaugeAttribute()
    {
        return number_format($this->product_gauge, 3);
    }

    public function getFormattedWaterGaugeAttribute()
    {
        return number_format($this->water_gauge, 3);
    }

    public function getFormattedTemperatureAttribute()
    {
        return number_format($this->temperature, 1);
    }

    public function getFormattedRoofWeightAttribute()
    {
        return $this->roof_weight ? number_format($this->roof_weight, 3) : 'N/A';
    }

    public function getFormattedDensityAttribute()
    {
        return number_format($this->density, 4);
    }

    public function getFormattedVcfAttribute()
    {
        return number_format($this->vcf, 4);
    }

    public function getFormattedTovAttribute()
    {
        return number_format($this->tov, 3);
    }

    public function getFormattedWaterVolumeAttribute()
    {
        return number_format($this->water_volume, 3);
    }

    public function getFormattedRoofVolumeAttribute()
    {
        return $this->roof_volume ? number_format($this->roof_volume, 3) : 'N/A';
    }

    public function getFormattedGovAttribute()
    {
        return number_format($this->gov, 3);
    }

    public function getFormattedGsvAttribute()
    {
        return number_format($this->gsv, 3);
    }

    public function getFormattedMtAirAttribute()
    {
        return number_format($this->mt_air, 3);
    }

    public function getFormattedMtVacAttribute()
    {
        return number_format($this->mt_vac, 3);
    }
}
