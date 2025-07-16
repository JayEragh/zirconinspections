<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use App\Models\Invoice;
use PDF;

class ClientController extends Controller
{
    /**
     * Show the client dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $client = $user->client;
        
        return view('client.dashboard', compact('user', 'client'));
    }

    /**
     * Show the client's service requests.
     */
    public function serviceRequests()
    {
        $user = Auth::user();
        $serviceRequests = $user->client->serviceRequests()->latest()->paginate(10);
        
        return view('client.service-requests', compact('serviceRequests'));
    }

    /**
     * Show the form to create a new service request.
     */
    public function createServiceRequest()
    {
        return view('client.create-service-request');
    }

    /**
     * Store a new service request.
     */
    public function storeServiceRequest(Request $request)
    {
        $request->validate([
            'depot' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity_gsv' => 'required|numeric|min:0',
            'quantity_mt' => 'required|numeric|min:0',
            'tank_numbers' => 'required|string',
            'service_type' => 'required|string',
            'specific_instructions' => 'nullable|string',
            'outturn_file' => 'nullable|file|mimes:pdf|max:2048',
            'quality_certificate_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $serviceRequest = $request->user()->client->serviceRequests()->create([
            'service_id' => \App\Models\ServiceRequest::generateServiceId(),
            'depot' => $request->depot,
            'product' => $request->product,
            'quantity_gsv' => $request->quantity_gsv,
            'quantity_mt' => $request->quantity_mt,
            'tank_numbers' => $request->tank_numbers,
            'service_type' => $request->service_type,
            'specific_instructions' => $request->specific_instructions,
            'status' => 'pending',
        ]);

        if ($request->hasFile('outturn_file')) {
            $serviceRequest->outturn_file = $request->file('outturn_file')->store('outturn_files', 'public');
            $serviceRequest->save();
        }

        if ($request->hasFile('quality_certificate_file')) {
            $serviceRequest->quality_certificate_file = $request->file('quality_certificate_file')->store('quality_certificates', 'public');
            $serviceRequest->save();
        }

        return redirect()->route('client.service-requests')->with('success', 'Service request created successfully!');
    }

    /**
     * Show a specific service request.
     */
    public function showServiceRequest($serviceRequest)
    {
        $user = Auth::user();
        $serviceRequest = $user->client->serviceRequests()->findOrFail($serviceRequest);
        
        return view('client.service-request-details', compact('serviceRequest'));
    }

    /**
     * Show the form to edit a service request.
     */
    public function editServiceRequest($serviceRequest)
    {
        $user = Auth::user();
        $serviceRequest = $user->client->serviceRequests()->findOrFail($serviceRequest);
        
        // Only allow editing if status is pending
        if ($serviceRequest->status !== 'pending') {
            return redirect()->route('client.service-requests')->with('error', 'Only pending service requests can be edited.');
        }
        
        return view('client.edit-service-request', compact('serviceRequest'));
    }

    /**
     * Update a service request.
     */
    public function updateServiceRequest(Request $request, $serviceRequest)
    {
        $user = Auth::user();
        $serviceRequest = $user->client->serviceRequests()->findOrFail($serviceRequest);
        
        // Only allow updating if status is pending
        if ($serviceRequest->status !== 'pending') {
            return redirect()->route('client.service-requests')->with('error', 'Only pending service requests can be updated.');
        }
        
        $request->validate([
            'depot' => 'required|string|max:255',
            'product' => 'required|string|max:255',
            'quantity_gsv' => 'required|numeric|min:0',
            'quantity_mt' => 'required|numeric|min:0',
            'tank_numbers' => 'required|string',
            'service_type' => 'required|string',
            'specific_instructions' => 'nullable|string',
            'outturn_file' => 'nullable|file|mimes:pdf|max:2048',
            'quality_certificate_file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $serviceRequest->update([
            'depot' => $request->depot,
            'product' => $request->product,
            'quantity_gsv' => $request->quantity_gsv,
            'quantity_mt' => $request->quantity_mt,
            'tank_numbers' => $request->tank_numbers,
            'service_type' => $request->service_type,
            'specific_instructions' => $request->specific_instructions,
        ]);

        if ($request->hasFile('outturn_file')) {
            $serviceRequest->outturn_file = $request->file('outturn_file')->store('outturn_files', 'public');
            $serviceRequest->save();
        }

        if ($request->hasFile('quality_certificate_file')) {
            $serviceRequest->quality_certificate_file = $request->file('quality_certificate_file')->store('quality_certificates', 'public');
            $serviceRequest->save();
        }

        return redirect()->route('client.service-requests')->with('success', 'Service request updated successfully!');
    }

    /**
     * Delete a service request.
     */
    public function deleteServiceRequest($serviceRequest)
    {
        $user = Auth::user();
        $serviceRequest = $user->client->serviceRequests()->findOrFail($serviceRequest);
        
        // Only allow deletion if status is pending
        if ($serviceRequest->status !== 'pending') {
            return redirect()->route('client.service-requests')->with('error', 'Only pending service requests can be deleted.');
        }
        
        $serviceRequest->delete();
        
        return redirect()->route('client.service-requests')->with('success', 'Service request deleted successfully!');
    }

    /**
     * Show the client's reports.
     */
    public function reports()
    {
        $user = Auth::user();
        $reports = $user->client->serviceRequests()
            ->with(['reports.inspector.user', 'reports.serviceRequest'])
            ->get()
            ->pluck('reports')
            ->flatten()
            ->sortByDesc('created_at');
        
        return view('client.reports', compact('reports'));
    }

    /**
     * Show the client's outturn reports.
     */
    public function outturnReports()
    {
        $user = Auth::user();
        $outturnReports = $user->client->serviceRequests()
            ->with(['outturnReports.inspector.user', 'outturnReports.serviceRequest'])
            ->get()
            ->pluck('outturnReports')
            ->flatten()
            ->sortByDesc('created_at');
        
        return view('client.outturn-reports', compact('outturnReports'));
    }

    /**
     * Show a specific outturn report.
     */
    public function showOutturnReport(\App\Models\OutturnReport $outturnReport)
    {
        // Ensure the client can only view their own outturn reports
        $user = Auth::user();
        $clientOutturnReports = $user->client->serviceRequests()
            ->with('outturnReports')
            ->get()
            ->pluck('outturnReports')
            ->flatten()
            ->pluck('id');

        if (!$clientOutturnReports->contains($outturnReport->id)) {
            abort(403, 'Unauthorized action.');
        }

        $outturnReport->load(['serviceRequest.client.user', 'inspector.user', 'outturnDataSets']);
        return view('client.outturn-report-details', compact('outturnReport'));
    }

    /**
     * Export outturn report as PDF.
     */
    public function exportOutturnReportPDF(\App\Models\OutturnReport $outturnReport)
    {
        // Ensure the client can only download their own outturn reports
        $user = Auth::user();
        $clientOutturnReports = $user->client->serviceRequests()
            ->with('outturnReports')
            ->get()
            ->pluck('outturnReports')
            ->flatten()
            ->pluck('id');

        if (!$clientOutturnReports->contains($outturnReport->id)) {
            abort(403, 'Unauthorized action.');
        }

        $outturnReport->load(['serviceRequest.client.user', 'inspector.user', 'outturnDataSets']);
        
        $pdf = PDF::loadView('outturn-reports.pdf', compact('outturnReport'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('outturn-report-' . $outturnReport->id . '.pdf');
    }

    /**
     * Show the client's invoices.
     */
    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->client->invoices()->latest()->paginate(10);
        
        return view('client.invoices', compact('invoices'));
    }

    /**
     * Show a specific invoice.
     */
    public function showInvoice(Invoice $invoice)
    {
        // Ensure the client can only view their own invoices
        $user = Auth::user();
        if ($invoice->client_id !== $user->client->id) {
            abort(403, 'Unauthorized action.');
        }

        $invoice->load(['serviceRequest.client.user']);
        return view('client.invoice-details', compact('invoice'));
    }

    /**
     * Upload payment evidence for an invoice.
     */
    public function uploadPaymentEvidence(Request $request, Invoice $invoice)
    {
        // Ensure the client can only upload evidence for their own invoices
        $user = Auth::user();
        if ($invoice->client_id !== $user->client->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'payment_evidence' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Handle file upload
        if ($request->hasFile('payment_evidence')) {
            $file = $request->file('payment_evidence');
            $fileName = 'payment_evidence_' . $invoice->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('payment_evidence', $fileName, 'public');
            
            $invoice->update([
                'payment_evidence' => 'payment_evidence/' . $fileName,
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Payment evidence uploaded successfully. Invoice marked as paid.');
        }

        return redirect()->back()->with('error', 'No file was uploaded.');
    }

    /**
     * Show the client's messages.
     */
    public function messages()
    {
        $user = Auth::user();
        $messages = $user->messages()->latest()->paginate(20);
        
        return view('client.messages', compact('messages'));
    }

    /**
     * Show a specific report.
     */
    public function showReport(Report $report)
    {
        // Ensure the client can only view their own reports
        $user = Auth::user();
        $clientReports = $user->client->serviceRequests()
            ->with('reports')
            ->get()
            ->pluck('reports')
            ->flatten()
            ->pluck('id');

        if (!$clientReports->contains($report->id)) {
            abort(403, 'Unauthorized action.');
        }

        $report->load(['serviceRequest.client.user', 'inspector.user', 'inspectionDataSets']);
        return view('client.report-details', compact('report'));
    }

    /**
     * Export report as PDF.
     */
    public function exportReportPDF(Report $report)
    {
        // Ensure the client can only download their own reports
        $user = Auth::user();
        $clientReports = $user->client->serviceRequests()
            ->with('reports')
            ->get()
            ->pluck('reports')
            ->flatten()
            ->pluck('id');

        if (!$clientReports->contains($report->id)) {
            abort(403, 'Unauthorized action.');
        }

        $report->load(['serviceRequest.client.user', 'inspector.user']);
        
        $pdf = PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download('report-' . $report->id . '.pdf');
    }

    /**
     * Export report inspection data as Excel.
     */
    public function exportReportExcel(Report $report)
    {
        // Ensure the client can only download their own reports
        $user = Auth::user();
        $clientReports = $user->client->serviceRequests()
            ->with('reports')
            ->get()
            ->pluck('reports')
            ->flatten()
            ->pluck('id');

        if (!$clientReports->contains($report->id)) {
            abort(403, 'Unauthorized action.');
        }

        $report->load(['serviceRequest.client.user', 'inspector.user', 'inspectionDataSets']);

        // Create CSV content (Excel-compatible)
        $filename = 'inspection-data-report-' . $report->id . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($report) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Date',
                'Time', 
                'Tank #',
                'Product Gauge',
                'Water Gauge',
                'Temperature (°C)',
                'Density',
                'VCF',
                'TOV',
                'Water Vol',
                'GOV',
                'GSV',
                'MT Air'
            ]);
            
            // Add data rows
            foreach ($report->inspectionDataSets as $dataSet) {
                fputcsv($file, [
                    $dataSet->inspection_date && is_object($dataSet->inspection_date) ? $dataSet->inspection_date->format('M d, Y') : ($dataSet->inspection_date ? $dataSet->inspection_date : 'N/A'),
                    $dataSet->inspection_time && is_object($dataSet->inspection_time) ? $dataSet->inspection_time->format('H:i') : ($dataSet->inspection_time ? $dataSet->inspection_time : 'N/A'),
                    $dataSet->tank_number,
                    number_format($dataSet->product_gauge, 3),
                    number_format($dataSet->water_gauge, 3),
                    number_format($dataSet->temperature, 1),
                    number_format($dataSet->density, 4),
                    number_format($dataSet->vcf, 4),
                    number_format($dataSet->tov, 3),
                    number_format($dataSet->water_volume, 3),
                    number_format($dataSet->gov, 3),
                    number_format($dataSet->gsv, 3),
                    number_format($dataSet->mt_air, 3)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the client's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $client = $user->client;
        
        return view('client.profile', compact('user', 'client'));
    }

    /**
     * Update the client's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the client's settings.
     */
    public function settings()
    {
        $user = Auth::user();
        
        return view('client.settings', compact('user'));
    }

    /**
     * Update the client's settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
        ]);
        
        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }
        
        // Update notification preferences
        $user->update([
            'notifications_email' => $request->has('notifications_email'),
            'notifications_sms' => $request->has('notifications_sms'),
        ]);
        
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
