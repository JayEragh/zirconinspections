<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Inspector;
use App\Models\ServiceRequest;
use App\Models\Report;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\LoginLog;
use App\Services\NotificationService;
use App\Services\AuditService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class OperationsController extends Controller
{
    /**
     * Show the operations dashboard.
     */
    public function dashboard(Request $request)
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_inspectors' => Inspector::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'completed_requests' => ServiceRequest::where('status', 'completed')->count(),
            'total_reports' => Report::count(),
            'total_invoices' => Invoice::count(),
        ];

        $recentRequests = ServiceRequest::with(['client.user', 'inspector.user'])
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with(['serviceRequest.client.user', 'inspector.user'])
            ->latest()
            ->take(5)
            ->get();

        $query = LoginLog::with('user');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhere('ip_address', 'like', "%$search%");
        }
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('logged_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('logged_at', '<=', $request->input('date_to'));
        }
        $loginLogs = $query->latest('logged_at')->paginate(5)->appends($request->except('page'));

        return view('operations.dashboard', compact('stats', 'recentRequests', 'recentReports', 'loginLogs'));
    }

    /**
     * Show all clients.
     */
    public function clients()
    {
        $clients = Client::with('user')->paginate(15);
        return view('operations.clients', compact('clients'));
    }

    /**
     * Show client details.
     */
    public function showClient(Client $client)
    {
        $client->load(['user', 'serviceRequests.reports', 'invoices']);
        return view('operations.client-details', compact('client'));
    }

    /**
     * Show all inspectors.
     */
    public function inspectors()
    {
        $inspectors = Inspector::with(['user', 'serviceRequests'])->paginate(15);
        return view('operations.inspectors', compact('inspectors'));
    }

    /**
     * Show inspector details.
     */
    public function showInspector(Inspector $inspector)
    {
        $inspector->load(['user', 'serviceRequests.reports']);
        return view('operations.inspector-details', compact('inspector'));
    }

    /**
     * Show the create inspector form.
     */
    public function createInspector()
    {
        return view('operations.create-inspector');
    }

    /**
     * Store a new inspector.
     */
    public function storeInspector(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'inspector',
        ]);

        // Create inspector profile
        Inspector::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'certification_number' => $request->employee_id,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('operations.inspectors')->with('success', 'Inspector created successfully!');
    }

    /**
     * Show the edit inspector form.
     */
    public function editInspector(Inspector $inspector)
    {
        return view('operations.edit-inspector', compact('inspector'));
    }

    /**
     * Update an inspector.
     */
    public function updateInspector(Request $request, Inspector $inspector)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $inspector->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'specialization' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        // Update user
        $inspector->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update inspector profile
        $inspector->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'certification_number' => $request->employee_id,
            'specialization' => $request->specialization,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('operations.inspectors')->with('success', 'Inspector updated successfully!');
    }

    /**
     * Delete an inspector.
     */
    public function deleteInspector(Inspector $inspector)
    {
        // Check if inspector has any assigned service requests
        if ($inspector->serviceRequests()->count() > 0) {
            return redirect()->route('operations.inspectors')->with('error', 'Cannot delete inspector with assigned service requests. Please reassign or complete the requests first.');
        }

        // Delete the user account
        $inspector->user->delete();

        // The inspector record will be deleted automatically due to cascade delete

        return redirect()->route('operations.inspectors')->with('success', 'Inspector deleted successfully!');
    }

    /**
     * Show the edit client form.
     */
    public function editClient(Client $client)
    {
        return view('operations.edit-client', compact('client'));
    }

    /**
     * Update a client.
     */
    public function updateClient(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $client->user_id,
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Update user
        $client->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update client profile
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('operations.clients')->with('success', 'Client updated successfully!');
    }

    /**
     * Delete a client.
     */
    public function deleteClient(Client $client)
    {
        // Check if client has any service requests
        if ($client->serviceRequests()->count() > 0) {
            return redirect()->route('operations.clients')->with('error', 'Cannot delete client with existing service requests. Please handle the requests first.');
        }

        // Delete the user account
        $client->user->delete();

        // The client record will be deleted automatically due to cascade delete

        return redirect()->route('operations.clients')->with('success', 'Client deleted successfully!');
    }

    /**
     * Show all service requests.
     */
    public function serviceRequests()
    {
        $serviceRequests = ServiceRequest::with(['client.user', 'inspector.user'])
            ->latest()
            ->paginate(15);
        return view('operations.service-requests', compact('serviceRequests'));
    }

    /**
     * Show service request details.
     */
    public function showServiceRequest(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['client.user', 'inspector.user', 'reports']);
        return view('operations.service-request-details', compact('serviceRequest'));
    }

    /**
     * Update service request status.
     */
    public function updateServiceRequest(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled',
        ]);

        $serviceRequest->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Service request status updated successfully!');
    }

    /**
     * Delete service request and all related data.
     */
    public function deleteServiceRequest(ServiceRequest $serviceRequest)
    {
        // Delete all related data
        // This will cascade delete reports, inspection data sets, messages, and invoices
        $serviceRequest->delete();

        return redirect()->route('operations.service-requests')->with('success', 'Service request and all related data deleted successfully!');
    }

    /**
     * Assign inspector to service request.
     */
    public function assignInspector(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'inspector_id' => 'required|exists:inspectors,id',
        ]);

        $serviceRequest->update([
            'inspector_id' => $request->inspector_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Send notification to assigned inspector
        $inspector = Inspector::find($request->inspector_id);
        NotificationService::create(
            $inspector->user_id,
            'service_request_assigned',
            'New Service Request Assigned',
            'You have been assigned to Service Request #' . $serviceRequest->id . ' - ' . ucfirst($serviceRequest->service_type) . ' at ' . $serviceRequest->depot,
            route('inspector.service-requests.show', $serviceRequest->id)
        );

        return back()->with('success', 'Inspector assigned successfully!');
    }

    /**
     * Show all reports.
     */
    public function reports()
    {
        $reports = Report::with(['serviceRequest.client.user', 'inspector.user'])
            ->latest()
            ->paginate(15);
        return view('operations.reports', compact('reports'));
    }

    /**
     * Show report details.
     */
    public function showReport(Report $report)
    {
        $report->load(['serviceRequest.client.user', 'inspector.user']);
        return view('operations.report-details', compact('report'));
    }

    /**
     * Approve a report.
     */
    public function approveReport(Report $report)
    {
        $report->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Send notification to inspector
        NotificationService::create(
            $report->inspector->user_id,
            'report_approved',
            'Report Approved',
            'Your report #' . $report->id . ' has been approved.',
            route('inspector.reports.show', $report->id)
        );

        // Log audit trail
        AuditService::logApproval($report, "Report #{$report->id} approved by operations");

        return back()->with('success', 'Report approved successfully!');
    }

    /**
     * Decline a report for amendment.
     */
    public function declineReport(Report $report)
    {
        $report->update([
            'status' => 'declined',
            'declined_at' => now(),
        ]);

        // Send notification to inspector
        NotificationService::create(
            $report->inspector->user_id,
            'report_declined',
            'Report Declined - Requires Amendment',
            'Your report #' . $report->id . ' has been declined and requires amendment.',
            route('inspector.reports.edit', $report->id)
        );

        // Log audit trail
        AuditService::logDecline($report, "Report #{$report->id} declined by operations");

        // Create notification message for inspector
        $message = Message::create([
            'sender_id' => Auth::id(), // Operations user
            'recipient_id' => $report->inspector->user_id,
            'subject' => 'Report Declined - Requires Amendment - Report #' . $report->id,
            'content' => "Dear " . $report->inspector->user->name . ",\n\n" .
                        "Your report #" . $report->id . " for Service Request #" . $report->serviceRequest->id . " has been declined and requires amendment.\n\n" .
                        "Report Details:\n" .
                        "- Report #: " . $report->id . "\n" .
                        "- Service Type: " . ucfirst($report->serviceRequest->service_type) . "\n" .
                        "- Depot: " . $report->serviceRequest->depot . "\n" .
                        "- Product: " . $report->serviceRequest->product . "\n" .
                        "- Client: " . $report->serviceRequest->client->user->name . "\n\n" .
                        "Please review the report and make the necessary amendments. You can edit the report from your inspector portal.\n\n" .
                        "Thank you for your attention to this matter.\n\n" .
                        "Best regards,\n" .
                        "Operations Team\n" .
                        "Zircon Inspections",
            'service_request_id' => $report->serviceRequest->id,
            'read' => false,
        ]);

        return back()->with('success', 'Report declined successfully! Inspector has been notified to make amendments.');
    }

    /**
     * Send approved report notification to client.
     */
    public function sendToClient(Report $report)
    {
        // Ensure report is approved
        if ($report->status !== 'approved') {
            return back()->with('error', 'Only approved reports can be sent to clients.');
        }

        // Create notification message for client
        $message = Message::create([
            'sender_id' => Auth::id(), // Operations user
            'recipient_id' => $report->client->user_id,
            'subject' => 'Report Approved - Service Request #' . $report->serviceRequest->id,
            'content' => "Dear " . $report->client->user->name . ",\n\n" .
                        "Your inspection report for Service Request #" . $report->serviceRequest->id . " has been approved and is ready for review.\n\n" .
                        "Report Details:\n" .
                        "- Report #: " . $report->id . "\n" .
                        "- Service Type: " . ucfirst($report->serviceRequest->service_type) . "\n" .
                        "- Depot: " . $report->serviceRequest->depot . "\n" .
                        "- Product: " . $report->serviceRequest->product . "\n" .
                        "- Inspector: " . $report->inspector->user->name . "\n\n" .
                        "You can view the complete report in your client portal.\n\n" .
                        "Thank you for choosing Zircon Inspections.\n\n" .
                        "Best regards,\n" .
                        "Operations Team\n" .
                        "Zircon Inspections",
            'service_request_id' => $report->serviceRequest->id,
            'read' => false,
        ]);

        // Update report to mark as sent to client
        $report->update([
            'sent_to_client_at' => now(),
        ]);

        return back()->with('success', 'Report notification sent to client successfully!');
    }

    /**
     * Export report as PDF.
     */
    public function exportReportPDF(Report $report)
    {
        $report->load(['serviceRequest.client.user', 'inspector.user', 'inspectionDataSets']);
        
        // Log audit trail
        AuditService::logExport($report, 'PDF', "Report #{$report->id} exported as PDF");
        
        $pdf = PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download('report-' . $report->id . '.pdf');
    }

    /**
     * Export report inspection data as Excel.
     */
    public function exportReportExcel(Report $report)
    {
        $report->load(['serviceRequest.client.user', 'inspector.user', 'inspectionDataSets']);

        // Log audit trail
        AuditService::logExport($report, 'Excel', "Report #{$report->id} exported as Excel");

        return Excel::download(new ReportExport($report), 'report-' . $report->id . '.xlsx');
    }

    /**
     * Show all outturn reports.
     */
    public function outturnReports()
    {
        $outturnReports = \App\Models\OutturnReport::with(['serviceRequest.client.user', 'inspector.user'])
            ->latest()
            ->paginate(15);
        return view('operations.outturn-reports', compact('outturnReports'));
    }

    /**
     * Show outturn report details.
     */
    public function showOutturnReport(\App\Models\OutturnReport $outturnReport)
    {
        $outturnReport->load(['serviceRequest.client.user', 'inspector.user', 'outturnDataSets']);
        return view('operations.outturn-report-details', compact('outturnReport'));
    }

    /**
     * Approve an outturn report.
     */
    public function approveOutturnReport(\App\Models\OutturnReport $outturnReport)
    {
        $outturnReport->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Send notification to inspector
        NotificationService::create(
            $outturnReport->inspector->user_id,
            'outturn_report_approved',
            'Outturn Report Approved',
            'Your outturn report #' . $outturnReport->id . ' has been approved.',
            route('inspector.outturn-reports.show', $outturnReport->id)
        );

        // Log audit trail
        AuditService::logApproval($outturnReport, "Outturn Report #{$outturnReport->id} approved by operations");

        return back()->with('success', 'Outturn report approved successfully!');
    }

    /**
     * Decline an outturn report for amendment.
     */
    public function declineOutturnReport(\App\Models\OutturnReport $outturnReport)
    {
        $outturnReport->update([
            'status' => 'declined',
            'declined_at' => now(),
        ]);

        // Send notification to inspector
        NotificationService::create(
            $outturnReport->inspector->user_id,
            'outturn_report_declined',
            'Outturn Report Declined - Requires Amendment',
            'Your outturn report #' . $outturnReport->id . ' has been declined and requires amendment.',
            route('inspector.outturn-reports.show', $outturnReport->id)
        );

        // Log audit trail
        AuditService::logDecline($outturnReport, "Outturn Report #{$outturnReport->id} declined by operations");

        // Create notification message for inspector
        $message = Message::create([
            'sender_id' => Auth::id(), // Operations user
            'recipient_id' => $outturnReport->inspector->user_id,
            'subject' => 'Outturn Report Declined - Requires Amendment - Report #' . $outturnReport->id,
            'content' => "Dear " . $outturnReport->inspector->user->name . ",\n\n" .
                        "Your outturn report #" . $outturnReport->id . " for Service Request #" . $outturnReport->serviceRequest->id . " has been declined and requires amendment.\n\n" .
                        "Report Details:\n" .
                        "- Report #: " . $outturnReport->id . "\n" .
                        "- Report Title: " . $outturnReport->report_title . "\n" .
                        "- Service Type: " . ucfirst($outturnReport->serviceRequest->service_type) . "\n" .
                        "- Depot: " . $outturnReport->serviceRequest->depot . "\n" .
                        "- Product: " . $outturnReport->serviceRequest->product . "\n" .
                        "- Client: " . $outturnReport->serviceRequest->client->user->name . "\n\n" .
                        "Please review the outturn report and make the necessary amendments. You can view the report from your inspector portal.\n\n" .
                        "Thank you for your attention to this matter.\n\n" .
                        "Best regards,\n" .
                        "Operations Team\n" .
                        "Zircon Inspections",
            'service_request_id' => $outturnReport->serviceRequest->id,
            'read' => false,
        ]);

        return back()->with('success', 'Outturn report declined successfully! Inspector has been notified to make amendments.');
    }

    /**
     * Send approved outturn report notification to client.
     */
    public function sendOutturnReportToClient(\App\Models\OutturnReport $outturnReport)
    {
        // Ensure outturn report is approved
        if ($outturnReport->status !== 'approved') {
            return back()->with('error', 'Only approved outturn reports can be sent to clients.');
        }

        // Create notification message for client
        $message = Message::create([
            'sender_id' => Auth::id(), // Operations user
            'recipient_id' => $outturnReport->client->user_id,
            'subject' => 'Outturn Report Approved - Service Request #' . $outturnReport->serviceRequest->id,
            'content' => "Dear " . $outturnReport->client->user->name . ",\n\n" .
                        "Your outturn report for Service Request #" . $outturnReport->serviceRequest->id . " has been approved and is ready for review.\n\n" .
                        "Report Details:\n" .
                        "- Report #: " . $outturnReport->id . "\n" .
                        "- Report Title: " . $outturnReport->report_title . "\n" .
                        "- Service Type: " . ucfirst($outturnReport->serviceRequest->service_type) . "\n" .
                        "- Depot: " . $outturnReport->serviceRequest->depot . "\n" .
                        "- Product: " . $outturnReport->serviceRequest->product . "\n" .
                        "- Inspector: " . $outturnReport->inspector->user->name . "\n\n" .
                        "You can view the complete outturn report in your client portal.\n\n" .
                        "Thank you for choosing Zircon Inspections.\n\n" .
                        "Best regards,\n" .
                        "Operations Team\n" .
                        "Zircon Inspections",
            'service_request_id' => $outturnReport->serviceRequest->id,
            'read' => false,
        ]);

        // Update outturn report to mark as sent to client
        $outturnReport->update([
            'sent_to_client_at' => now(),
        ]);

        return back()->with('success', 'Outturn report notification sent to client successfully!');
    }

    /**
     * Export outturn report as PDF.
     */
    public function exportOutturnReportPDF(\App\Models\OutturnReport $outturnReport)
    {
        $outturnReport->load(['serviceRequest.client.user', 'inspector.user', 'outturnDataSets']);
        
        // Log audit trail
        AuditService::logExport($outturnReport, 'PDF', "Outturn Report #{$outturnReport->id} exported as PDF");
        
        $pdf = PDF::loadView('outturn-reports.pdf', compact('outturnReport'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('outturn-report-' . $outturnReport->id . '.pdf');
    }

    /**
     * Show all invoices.
     */
    public function invoices()
    {
        $invoices = Invoice::with(['serviceRequest.client.user'])
            ->latest()
            ->paginate(15);
        return view('operations.invoices', compact('invoices'));
    }

    /**
     * Show invoice details.
     */
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['serviceRequest.client.user']);
        return view('operations.invoice-details', compact('invoice'));
    }

    /**
     * Show the create invoice form.
     */
    public function createInvoice()
    {
        $serviceRequests = ServiceRequest::with(['client.user'])
            ->where('status', 'completed')
            ->whereDoesntHave('invoice')
            ->get();
        
        $clients = Client::with('user')->get();
        
        return view('operations.create-invoice', compact('serviceRequests', 'clients'));
    }

    /**
     * Store a new invoice.
     */
    public function storeInvoice(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'due_date' => 'required|date|after:today',
        ]);

        // Check if invoice already exists for this service request
        $existingInvoice = Invoice::where('service_request_id', $request->service_request_id)->first();
        if ($existingInvoice) {
            return back()->with('error', 'An invoice already exists for this service request.');
        }

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'service_request_id' => $request->service_request_id,
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
            'due_date' => $request->due_date,
        ]);

        // Calculate taxes and total
        $invoice->calculateTaxes()->save();

        // Log audit trail
        AuditService::logCreate($invoice, "Invoice #{$invoice->invoice_number} created for Service Request #{$invoice->service_request_id}");

        return redirect()->route('operations.invoices')->with('success', 'Invoice created successfully!');
    }

    /**
     * Show the edit invoice form.
     */
    public function editInvoice(Invoice $invoice)
    {
        $serviceRequests = ServiceRequest::with(['client.user'])->get();
        $clients = Client::with('user')->get();
        
        return view('operations.edit-invoice', compact('invoice', 'serviceRequests', 'clients'));
    }

    /**
     * Update an invoice.
     */
    public function updateInvoice(Request $request, Invoice $invoice)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
        ]);

        $oldValues = $invoice->toArray();

        // Update invoice
        $invoice->update([
            'service_request_id' => $request->service_request_id,
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        // Recalculate taxes and total
        $invoice->calculateTaxes()->save();

        // Log audit trail
        AuditService::logUpdate($invoice, $oldValues, $invoice->toArray(), "Invoice #{$invoice->invoice_number} updated");

        return redirect()->route('operations.invoices')->with('success', 'Invoice updated successfully!');
    }

    /**
     * Delete an invoice.
     */
    public function deleteInvoice(Invoice $invoice)
    {
        $invoiceNumber = $invoice->invoice_number;
        
        // Log audit trail before deletion
        AuditService::logDelete($invoice, "Invoice #{$invoiceNumber} deleted");
        
        $invoice->delete();

        return redirect()->route('operations.invoices')->with('success', 'Invoice deleted successfully!');
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Log audit trail
        AuditService::log('mark_paid', "Invoice #{$invoice->invoice_number} marked as paid", $invoice);

        return back()->with('success', 'Invoice marked as paid successfully!');
    }

    /**
     * Approve invoice and send to client.
     */
    public function approveInvoice(Invoice $invoice)
    {
        // Approve the invoice and set payment deadline
        $invoice->approve();
        
        // Send to client
        $invoice->sendToClient();
        
        // Create notification for client
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $invoice->client->user_id,
            'subject' => 'Invoice Approved: ' . $invoice->invoice_number,
            'content' => "Your invoice #{$invoice->invoice_number} has been approved and is ready for payment.\n\n" .
                        "Amount: {$invoice->formatted_total}\n" .
                        "Payment Deadline: {$invoice->payment_deadline->format('M d, Y')}\n\n" .
                        "Please log in to your dashboard to view the complete invoice details.",
            'service_request_id' => $invoice->service_request_id,
        ]);
        
        // Log audit trail
        AuditService::log('approve_invoice', "Invoice #{$invoice->invoice_number} approved and sent to client", $invoice);

        return back()->with('success', 'Invoice approved and sent to client successfully!');
    }

    /**
     * Undo approval for an invoice.
     */
    public function undoApproval(Invoice $invoice)
    {
        // Check if invoice is approved
        if ($invoice->status !== 'approved') {
            return back()->with('error', 'Only approved invoices can have their approval undone.');
        }

        // Check if invoice has been paid
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot undo approval for paid invoices.');
        }

        // Undo approval
        $invoice->undoApproval();
        
        // Create notification for client about approval being revoked
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $invoice->client->user_id,
            'subject' => 'Invoice Approval Revoked: ' . $invoice->invoice_number,
            'content' => "The approval for invoice #{$invoice->invoice_number} has been revoked.\n\n" .
                        "The invoice is now back in draft status and will need to be re-approved before payment can be processed.\n\n" .
                        "We apologize for any inconvenience this may cause.",
            'service_request_id' => $invoice->service_request_id,
        ]);
        
        // Log audit trail
        AuditService::log('undo_approval', "Invoice #{$invoice->invoice_number} approval undone", $invoice);

        return back()->with('success', 'Invoice approval undone successfully! Client has been notified.');
    }

    /**
     * Send overdue notification for invoice.
     */
    public function sendOverdueNotification(Invoice $invoice)
    {
        if (!$invoice->isOverdue()) {
            return back()->with('error', 'Invoice is not overdue.');
        }
        
        // Send notification to client
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $invoice->client->user_id,
            'subject' => 'URGENT: Overdue Invoice - ' . $invoice->invoice_number,
            'content' => "Your invoice #{$invoice->invoice_number} is overdue by {$invoice->getOverdueDays()} days.\n\n" .
                        "Amount Due: {$invoice->formatted_total}\n" .
                        "Original Deadline: {$invoice->payment_deadline->format('M d, Y')}\n\n" .
                        "Please make payment immediately to avoid any additional charges.",
            'service_request_id' => $invoice->service_request_id,
        ]);
        
        // Mark notification as sent
        $invoice->markOverdueNotificationSent();
        
        // Log audit trail
        AuditService::log('send_overdue_notification', "Overdue notification sent for Invoice #{$invoice->invoice_number}", $invoice);

        return back()->with('success', 'Overdue notification sent to client successfully!');
    }

    /**
     * Show all messages.
     */
    public function messages()
    {
        $messages = Message::with(['sender', 'recipient', 'serviceRequest'])
            ->latest()
            ->paginate(20);
        return view('operations.messages', compact('messages'));
    }

    /**
     * Show message details.
     */
    public function showMessage(Message $message)
    {
        $message->load(['sender', 'recipient', 'serviceRequest']);
        
        // Mark message as read if it hasn't been read yet
        if (!$message->read) {
            $message->update([
                'read' => true,
                'read_at' => now(),
            ]);
        }
        
        return view('operations.message-details', compact('message'));
    }

    public function sendReportToClient($report)
    {
        $report = Report::with(['serviceRequest', 'client'])->findOrFail($report);
        
        // Create a message to notify the client
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $report->client->user_id,
            'subject' => 'Report Available: ' . $report->title,
            'content' => 'Your inspection report "' . $report->title . '" is now available for review. You can view and download it from your dashboard.',
            'service_request_id' => $report->service_request_id,
        ]);
        
        // Mark report as sent to client
        $report->update(['sent_to_client' => true]);
        
        return redirect()->back()->with('success', 'Report sent to client successfully.');
    }

    /**
     * Show the operations user's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('operations.profile', compact('user'));
    }

    /**
     * Update the operations user's profile.
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
     * Show the operations user's settings.
     */
    public function settings()
    {
        $user = Auth::user();
        
        return view('operations.settings', compact('user'));
    }

    /**
     * Update the operations user's settings.
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

    /**
     * Export login logs as Excel.
     */
    public function exportLoginLogs(Request $request)
    {
        $query = LoginLog::with('user');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhere('ip_address', 'like', "%$search%");
        }
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('logged_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('logged_at', '<=', $request->input('date_to'));
        }
        
        $loginLogs = $query->latest('logged_at')->get();

        // Create CSV content
        $filename = 'login-logs-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($loginLogs) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Date/Time',
                'User',
                'Email',
                'Action',
                'IP Address',
                'User Agent'
            ]);
            
            // Add data rows
            foreach ($loginLogs as $log) {
                fputcsv($file, [
                    $log->logged_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'N/A',
                    $log->user ? $log->user->email : 'N/A',
                    $log->action,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show audit logs.
     */
    public function auditLogs(Request $request)
    {
        $query = \App\Models\AuditLog::with('user');
        
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
        
        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->input('model_type'));
        }
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        
        // Search in description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%$search%");
        }
        
        $auditLogs = $query->latest()->paginate(20)->appends($request->except('page'));
        
        // Get available filter options
        $actions = \App\Models\AuditLog::distinct()->pluck('action');
        $modelTypes = \App\Models\AuditLog::distinct()->pluck('model_type')->filter();
        $users = User::orderBy('name')->get();
        
        return view('operations.audit-logs', compact('auditLogs', 'actions', 'modelTypes', 'users'));
    }

    // User Management Methods

    /**
     * Show all users.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        // Filter by email verification
        if ($request->filled('verified')) {
            if ($request->input('verified') === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->latest()->paginate(15)->appends($request->except('page'));

        return view('operations.users', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        $user->load(['client', 'inspector']);
        
        // Get related data
        $serviceRequests = [];
        $reports = [];
        $invoices = [];
        
        if ($user->isClient() && $user->client) {
            $serviceRequests = $user->client->serviceRequests()->with('inspector.user')->latest()->take(10)->get();
            $invoices = $user->client->invoices()->latest()->take(10)->get();
        }
        
        if ($user->isInspector() && $user->inspector) {
            $serviceRequests = $user->inspector->serviceRequests()->with('client.user')->latest()->take(10)->get();
            $reports = $user->inspector->reports()->with('serviceRequest.client.user')->latest()->take(10)->get();
        }

        // Get login logs
        $loginLogs = LoginLog::where('user_id', $user->id)->latest()->take(10)->get();

        return view('operations.user-details', compact('user', 'serviceRequests', 'reports', 'invoices', 'loginLogs'));
    }

    /**
     * Show the create user form.
     */
    public function createUser()
    {
        return view('operations.create-user');
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:client,inspector,operations',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'email_verified' => 'boolean',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
            // Role-specific fields
            'company_name' => 'required_if:role,client|string|max:255',
            'specialization' => 'required_if:role,inspector|string|max:255',
            'license_number' => 'nullable|string|max:100',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
            'notifications_email' => $request->boolean('notifications_email', true),
            'notifications_sms' => $request->boolean('notifications_sms', false),
        ];

        $user = User::create($userData);

        // Create role-specific records
        if ($request->role === 'client') {
            Client::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
            ]);
        } elseif ($request->role === 'inspector') {
            Inspector::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
            ]);
        }

        // Audit log
        app(AuditService::class)->log(
            'created',
            User::class,
            $user->id,
            "User '{$user->name}' was created with role '{$user->role}'"
        );

        return redirect()->route('operations.users')->with('success', 'User created successfully.');
    }

    /**
     * Show the edit user form.
     */
    public function editUser(User $user)
    {
        $user->load(['client', 'inspector']);
        return view('operations.edit-user', compact('user'));
    }

    /**
     * Update a user.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:client,inspector,operations',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'email_verified' => 'boolean',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
            // Role-specific fields
            'company_name' => 'required_if:role,client|string|max:255',
            'specialization' => 'required_if:role,inspector|string|max:255',
            'license_number' => 'nullable|string|max:100',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => $request->boolean('email_verified') ? ($user->email_verified_at ?: now()) : null,
            'notifications_email' => $request->boolean('notifications_email', true),
            'notifications_sms' => $request->boolean('notifications_sms', false),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Handle role-specific updates
        if ($request->role === 'client') {
            // Delete inspector record if exists
            if ($user->inspector) {
                $user->inspector->delete();
            }
            
            // Create or update client record
            $user->client()->updateOrCreate(
                ['user_id' => $user->id],
                ['company_name' => $request->company_name]
            );
        } elseif ($request->role === 'inspector') {
            // Delete client record if exists
            if ($user->client) {
                $user->client->delete();
            }
            
            // Create or update inspector record
            $user->inspector()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $request->specialization,
                    'license_number' => $request->license_number,
                ]
            );
        } else {
            // Operations role - delete both client and inspector records
            if ($user->client) {
                $user->client->delete();
            }
            if ($user->inspector) {
                $user->inspector->delete();
            }
        }

        // Audit log
        app(AuditService::class)->log(
            'updated',
            User::class,
            $user->id,
            "User '{$user->name}' was updated"
        );

        return redirect()->route('operations.users')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('operations.users')->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;

        // Delete related records
        if ($user->client) {
            $user->client->delete();
        }
        if ($user->inspector) {
            $user->inspector->delete();
        }

        $user->delete();

        // Audit log
        app(AuditService::class)->log(
            'deleted',
            User::class,
            $user->id,
            "User '{$userName}' was deleted"
        );

        return redirect()->route('operations.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleUserStatus(User $user)
    {
        // Prevent deactivating the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('operations.users')->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        // Audit log
        app(AuditService::class)->log(
            'updated',
            User::class,
            $user->id,
            "User '{$user->name}' was {$status}"
        );

        return redirect()->route('operations.users')->with('success', "User {$status} successfully.");
    }
}
