<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Models\OutturnReport;
use App\Models\OutturnDataSet;
use App\Models\Inspector;
use PDF;

class InspectorOutturnController extends Controller
{
    /**
     * Show the form to create an outturn report.
     */
    public function createOutturnReport($serviceRequestId)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $serviceRequest = ServiceRequest::where('id', $serviceRequestId)
            ->where('inspector_id', $inspector->id)
            ->with(['client'])
            ->firstOrFail();
            
        $tankNumbers = array_map('trim', explode(',', $serviceRequest->tank_numbers));
        
        return view('inspector.create-outturn-report', compact('serviceRequest', 'tankNumbers'));
    }

    /**
     * Store the outturn report.
     */
    public function storeOutturnReport(Request $request, $serviceRequestId)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $serviceRequest = ServiceRequest::where('id', $serviceRequestId)
            ->where('inspector_id', $inspector->id)
            ->firstOrFail();
        
        $request->validate([
            'report_title' => 'required|string|max:255',
            'report_date' => 'required|date',
            'tanks' => 'required|array|min:1',
            'tanks.*.tank_number' => 'required|string|max:50',
            'tanks.*.initial_data' => 'required|array',
            'tanks.*.final_data' => 'required|array',
            'tanks.*.initial_data.inspection_date' => 'required|date',
            'tanks.*.initial_data.inspection_time' => 'required',
            'tanks.*.initial_data.product_gauge' => 'required|numeric|min:0',
            'tanks.*.initial_data.water_gauge' => 'required|numeric|min:0',
            'tanks.*.initial_data.temperature' => 'required|numeric',
            'tanks.*.initial_data.has_roof' => 'required|in:0,1',
            'tanks.*.initial_data.roof_weight' => 'nullable|numeric|min:0',
            'tanks.*.initial_data.density' => 'required|numeric|min:0',
            'tanks.*.initial_data.vcf' => 'required|numeric|min:0',
            'tanks.*.initial_data.tov' => 'required|numeric|min:0',
            'tanks.*.initial_data.water_volume' => 'required|numeric|min:0',
            'tanks.*.initial_data.roof_volume' => 'nullable|numeric',
            'tanks.*.initial_data.gov' => 'required|numeric',
            'tanks.*.initial_data.gsv' => 'required|numeric',
            'tanks.*.initial_data.mt_air' => 'required|numeric',
            'tanks.*.initial_data.mt_vac' => 'required|numeric',
            'tanks.*.initial_data.notes' => 'nullable|string',
            'tanks.*.final_data.inspection_date' => 'required|date',
            'tanks.*.final_data.inspection_time' => 'required',
            'tanks.*.final_data.product_gauge' => 'required|numeric|min:0',
            'tanks.*.final_data.water_gauge' => 'required|numeric|min:0',
            'tanks.*.final_data.temperature' => 'required|numeric',
            'tanks.*.final_data.has_roof' => 'required|in:0,1',
            'tanks.*.final_data.roof_weight' => 'nullable|numeric|min:0',
            'tanks.*.final_data.density' => 'required|numeric|min:0',
            'tanks.*.final_data.vcf' => 'required|numeric|min:0',
            'tanks.*.final_data.tov' => 'required|numeric|min:0',
            'tanks.*.final_data.water_volume' => 'required|numeric|min:0',
            'tanks.*.final_data.roof_volume' => 'nullable|numeric',
            'tanks.*.final_data.gov' => 'required|numeric',
            'tanks.*.final_data.gsv' => 'required|numeric',
            'tanks.*.final_data.mt_air' => 'required|numeric',
            'tanks.*.final_data.mt_vac' => 'required|numeric',
            'tanks.*.final_data.notes' => 'nullable|string',
        ]);

        // Create outturn report
        $outturnReport = OutturnReport::create([
            'report_title' => $request->report_title,
            'service_request_id' => $serviceRequest->id,
            'inspector_id' => $inspector->id,
            'client_id' => $serviceRequest->client_id,
            'bdc_name' => $serviceRequest->depot,
            'report_date' => $request->report_date,
        ]);

        // Create data sets for each tank
        foreach ($request->tanks as $tankData) {
            $tankNumber = $tankData['tank_number'];
            
            // Create initial data set
            $outturnReport->outturnDataSets()->create([
                'tank_number' => $tankNumber,
                'data_type' => 'initial',
                'inspection_date' => $tankData['initial_data']['inspection_date'],
                'inspection_time' => $tankData['initial_data']['inspection_time'],
                'product_gauge' => $tankData['initial_data']['product_gauge'],
                'water_gauge' => $tankData['initial_data']['water_gauge'],
                'temperature' => $tankData['initial_data']['temperature'],
                'has_roof' => $tankData['initial_data']['has_roof'],
                'roof_weight' => $tankData['initial_data']['roof_weight'] ?? null,
                'density' => $tankData['initial_data']['density'],
                'vcf' => $tankData['initial_data']['vcf'],
                'tov' => $tankData['initial_data']['tov'],
                'water_volume' => $tankData['initial_data']['water_volume'],
                'roof_volume' => $tankData['initial_data']['roof_volume'] ?? null,
                'gov' => $tankData['initial_data']['gov'],
                'gsv' => $tankData['initial_data']['gsv'],
                'mt_air' => $tankData['initial_data']['mt_air'],
                'mt_vac' => $tankData['initial_data']['mt_vac'],
                'notes' => $tankData['initial_data']['notes'] ?? null,
            ]);

            // Create final data set
            $outturnReport->outturnDataSets()->create([
                'tank_number' => $tankNumber,
                'data_type' => 'final',
                'inspection_date' => $tankData['final_data']['inspection_date'],
                'inspection_time' => $tankData['final_data']['inspection_time'],
                'product_gauge' => $tankData['final_data']['product_gauge'],
                'water_gauge' => $tankData['final_data']['water_gauge'],
                'temperature' => $tankData['final_data']['temperature'],
                'has_roof' => $tankData['final_data']['has_roof'],
                'roof_weight' => $tankData['final_data']['roof_weight'] ?? null,
                'density' => $tankData['final_data']['density'],
                'vcf' => $tankData['final_data']['vcf'],
                'tov' => $tankData['final_data']['tov'],
                'water_volume' => $tankData['final_data']['water_volume'],
                'roof_volume' => $tankData['final_data']['roof_volume'] ?? null,
                'gov' => $tankData['final_data']['gov'],
                'gsv' => $tankData['final_data']['gsv'],
                'mt_air' => $tankData['final_data']['mt_air'],
                'mt_vac' => $tankData['final_data']['mt_vac'],
                'notes' => $tankData['final_data']['notes'] ?? null,
            ]);
        }

        // Calculate totals
        $outturnReport->calculateTotals();

        return redirect()->route('inspector.outturn-reports')->with('success', 'Outturn report created successfully.');
    }

    /**
     * Show all outturn reports for the inspector.
     */
    public function outturnReports()
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $outturnReports = OutturnReport::where('inspector_id', $inspector->id)
            ->with(['serviceRequest.client.user'])
            ->latest()
            ->paginate(10);
            
        return view('inspector.outturn-reports', compact('outturnReports'));
    }

    /**
     * Show a specific outturn report.
     */
    public function showOutturnReport(OutturnReport $outturnReport)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector || $outturnReport->inspector_id !== $inspector->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $outturnReport->load(['serviceRequest.client.user', 'outturnDataSets']);
        
        return view('inspector.outturn-report-details', compact('outturnReport'));
    }

    /**
     * Export outturn report as PDF.
     */
    public function exportOutturnReportPDF(OutturnReport $outturnReport)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector || $outturnReport->inspector_id !== $inspector->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $outturnReport->load(['serviceRequest.client.user', 'outturnDataSets']);
        
        $pdf = PDF::loadView('outturn-reports.pdf', compact('outturnReport'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('outturn-report-' . $outturnReport->id . '.pdf');
    }
}
