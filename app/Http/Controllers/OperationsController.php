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
        $loginLogs = $query->latest('logged_at')->paginate(20)->appends($request->except('page'));

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
        AuditService::logExport($report, 'Excel', "Report #{$report->id} inspection data exported as Excel");

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
}
