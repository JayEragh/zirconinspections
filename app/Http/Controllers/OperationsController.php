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

class OperationsController extends Controller
{
    /**
     * Show the operations dashboard.
     */
    public function dashboard()
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

        return view('operations.dashboard', compact('stats', 'recentRequests', 'recentReports'));
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
            'license_number' => 'nullable|string|max:50',
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
            'certification_number' => $request->license_number,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('operations.inspectors')->with('success', 'Inspector created successfully!');
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

        return back()->with('success', 'Report approved successfully!');
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
}
