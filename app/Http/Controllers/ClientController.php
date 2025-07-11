<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;

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
     * Show the client's invoices.
     */
    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->client->invoices()->latest()->paginate(10);
        
        return view('client.invoices', compact('invoices'));
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

        $report->load(['serviceRequest.client.user', 'inspector.user']);
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
        
        $pdf = \PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download('report-' . $report->id . '.pdf');
    }
}
