<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutturnReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_title',
        'service_request_id',
        'inspector_id',
        'client_id',
        'bdc_name',
        'report_date',
        'total_gov_initial',
        'total_gov_final',
        'total_gsv_initial',
        'total_gsv_final',
        'total_mt_air_initial',
        'total_mt_air_final',
        'total_mt_vac_initial',
        'total_mt_vac_final',
        'total_gov_difference',
        'total_gsv_difference',
        'total_mt_air_difference',
        'total_mt_vac_difference',
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_gov_initial' => 'decimal:3',
        'total_gov_final' => 'decimal:3',
        'total_gsv_initial' => 'decimal:3',
        'total_gsv_final' => 'decimal:3',
        'total_mt_air_initial' => 'decimal:3',
        'total_mt_air_final' => 'decimal:3',
        'total_mt_vac_initial' => 'decimal:3',
        'total_mt_vac_final' => 'decimal:3',
        'total_gov_difference' => 'decimal:3',
        'total_gsv_difference' => 'decimal:3',
        'total_mt_air_difference' => 'decimal:3',
        'total_mt_vac_difference' => 'decimal:3',
    ];

    /**
     * Get the service request that owns the outturn report.
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the inspector that created the outturn report.
     */
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    /**
     * Get the client that owns the outturn report.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the outturn data sets for this report.
     */
    public function outturnDataSets()
    {
        return $this->hasMany(OutturnDataSet::class);
    }

    /**
     * Get initial data sets.
     */
    public function initialDataSets()
    {
        return $this->outturnDataSets()->where('data_type', 'initial');
    }

    /**
     * Get final data sets.
     */
    public function finalDataSets()
    {
        return $this->outturnDataSets()->where('data_type', 'final');
    }

    /**
     * Calculate totals from data sets.
     */
    public function calculateTotals()
    {
        $initialData = $this->initialDataSets()->get();
        $finalData = $this->finalDataSets()->get();

        // Calculate initial totals
        $this->total_gov_initial = $initialData->sum('gov');
        $this->total_gsv_initial = $initialData->sum('gsv');
        $this->total_mt_air_initial = $initialData->sum('mt_air');
        $this->total_mt_vac_initial = $initialData->sum('mt_vac');

        // Calculate final totals
        $this->total_gov_final = $finalData->sum('gov');
        $this->total_gsv_final = $finalData->sum('gsv');
        $this->total_mt_air_final = $finalData->sum('mt_air');
        $this->total_mt_vac_final = $finalData->sum('mt_vac');

        // Calculate differences
        $this->total_gov_difference = $this->total_gov_final - $this->total_gov_initial;
        $this->total_gsv_difference = $this->total_gsv_final - $this->total_gsv_initial;
        $this->total_mt_air_difference = $this->total_mt_air_final - $this->total_mt_air_initial;
        $this->total_mt_vac_difference = $this->total_mt_vac_final - $this->total_mt_vac_initial;

        $this->save();
    }

    /**
     * Get formatted totals.
     */
    public function getFormattedTotalGovInitialAttribute()
    {
        return number_format($this->total_gov_initial, 3);
    }

    public function getFormattedTotalGovFinalAttribute()
    {
        return number_format($this->total_gov_final, 3);
    }

    public function getFormattedTotalGsvInitialAttribute()
    {
        return number_format($this->total_gsv_initial, 3);
    }

    public function getFormattedTotalGsvFinalAttribute()
    {
        return number_format($this->total_gsv_final, 3);
    }

    public function getFormattedTotalMtAirInitialAttribute()
    {
        return number_format($this->total_mt_air_initial, 3);
    }

    public function getFormattedTotalMtAirFinalAttribute()
    {
        return number_format($this->total_mt_air_final, 3);
    }

    public function getFormattedTotalMtVacInitialAttribute()
    {
        return number_format($this->total_mt_vac_initial, 3);
    }

    public function getFormattedTotalMtVacFinalAttribute()
    {
        return number_format($this->total_mt_vac_final, 3);
    }

    public function getFormattedTotalGovDifferenceAttribute()
    {
        return number_format($this->total_gov_difference, 3);
    }

    public function getFormattedTotalGsvDifferenceAttribute()
    {
        return number_format($this->total_gsv_difference, 3);
    }

    public function getFormattedTotalMtAirDifferenceAttribute()
    {
        return number_format($this->total_mt_air_difference, 3);
    }

    public function getFormattedTotalMtVacDifferenceAttribute()
    {
        return number_format($this->total_mt_vac_difference, 3);
    }
}
