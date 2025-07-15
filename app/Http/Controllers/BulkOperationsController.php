<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Models\Report;
use App\Models\User;
use App\Models\Client;
use App\Models\Inspector;
use App\Services\NotificationService;
use App\Services\AuditService;

class BulkOperationsController extends Controller
{
    /**
     * Bulk assign inspectors to service requests.
     */
    public function bulkAssignInspectors(Request $request)
    {
        $request->validate([
            'service_request_ids' => 'required|array',
            'service_request_ids.*' => 'exists:service_requests,id',
            'inspector_id' => 'required|exists:inspectors,id',
        ]);

        $serviceRequests = ServiceRequest::whereIn('id', $request->service_request_ids)->get();
        $inspector = Inspector::find($request->inspector_id);
        $assignedCount = 0;

        foreach ($serviceRequests as $serviceRequest) {
            if ($serviceRequest->status === 'pending') {
                $serviceRequest->update([
                    'inspector_id' => $request->inspector_id,
                    'status' => 'assigned',
                    'assigned_at' => now(),
                ]);

                // Send notification to inspector
                NotificationService::create(
                    $inspector->user_id,
                    'service_request_assigned',
                    'New Service Request Assigned',
                    'You have been assigned to Service Request #' . $serviceRequest->id . ' - ' . ucfirst($serviceRequest->service_type) . ' at ' . $serviceRequest->depot,
                    route('inspector.service-requests.show', $serviceRequest->id)
                );

                // Log audit trail
                AuditService::log('bulk_assign', "Service Request #{$serviceRequest->id} assigned to {$inspector->user->name}", $serviceRequest);

                $assignedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully assigned {$assignedCount} service requests to {$inspector->user->name}",
            'assigned_count' => $assignedCount
        ]);
    }

    /**
     * Bulk approve reports.
     */
    public function bulkApproveReports(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
        ]);

        $reports = Report::whereIn('id', $request->report_ids)->get();
        $approvedCount = 0;

        foreach ($reports as $report) {
            if ($report->status === 'pending') {
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
                AuditService::logApproval($report, "Report #{$report->id} approved in bulk operation");

                $approvedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully approved {$approvedCount} reports",
            'approved_count' => $approvedCount
        ]);
    }

    /**
     * Bulk decline reports.
     */
    public function bulkDeclineReports(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $reports = Report::whereIn('id', $request->report_ids)->get();
        $declinedCount = 0;

        foreach ($reports as $report) {
            if ($report->status === 'pending') {
                $report->update([
                    'status' => 'declined',
                    'declined_at' => now(),
                ]);

                // Send notification to inspector
                NotificationService::create(
                    $report->inspector->user_id,
                    'report_declined',
                    'Report Declined - Requires Amendment',
                    'Your report #' . $report->id . ' has been declined and requires amendment.' . ($request->reason ? ' Reason: ' . $request->reason : ''),
                    route('inspector.reports.edit', $report->id)
                );

                // Log audit trail
                AuditService::logDecline($report, "Report #{$report->id} declined in bulk operation" . ($request->reason ? ' - Reason: ' . $request->reason : ''));

                $declinedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully declined {$declinedCount} reports",
            'declined_count' => $declinedCount
        ]);
    }

    /**
     * Bulk send reports to clients.
     */
    public function bulkSendToClients(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
        ]);

        $reports = Report::whereIn('id', $request->report_ids)->where('status', 'approved')->get();
        $sentCount = 0;

        foreach ($reports as $report) {
            // Send notification to client
            NotificationService::create(
                $report->client->user_id,
                'report_available',
                'Report Available',
                'Your report #' . $report->id . ' is now available for review.',
                route('client.reports.show', $report->id)
            );

            // Update report to mark as sent to client
            $report->update([
                'sent_to_client_at' => now(),
            ]);

            // Log audit trail
            AuditService::log('bulk_send', "Report #{$report->id} sent to client in bulk operation", $report);

            $sentCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully sent {$sentCount} reports to clients",
            'sent_count' => $sentCount
        ]);
    }

    /**
     * Bulk export reports as PDF.
     */
    public function bulkExportPDF(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
        ]);

        $reports = Report::whereIn('id', $request->report_ids)->with(['serviceRequest.client.user', 'inspector.user', 'inspectionDataSets'])->get();

        // Log audit trail
        foreach ($reports as $report) {
            AuditService::logExport($report, 'PDF', "Report #{$report->id} exported as PDF in bulk operation");
        }

        // For now, return success - in a real implementation, you'd create a ZIP file
        return response()->json([
            'success' => true,
            'message' => "Prepared {$reports->count()} reports for PDF export",
            'report_count' => $reports->count()
        ]);
    }

    /**
     * Bulk export reports as Excel.
     */
    public function bulkExportExcel(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
        ]);

        $reports = Report::whereIn('id', $request->report_ids)->with(['serviceRequest.client.user', 'inspector.user', 'inspectionDataSets'])->get();

        // Log audit trail
        foreach ($reports as $report) {
            AuditService::logExport($report, 'Excel', "Report #{$report->id} exported as Excel in bulk operation");
        }

        // For now, return success - in a real implementation, you'd create a ZIP file
        return response()->json([
            'success' => true,
            'message' => "Prepared {$reports->count()} reports for Excel export",
            'report_count' => $reports->count()
        ]);
    }

    /**
     * Bulk deactivate users.
     */
    public function bulkDeactivateUsers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $deactivatedCount = 0;

        foreach ($users as $user) {
            if ($user->id !== Auth::id()) { // Prevent self-deactivation
                $user->update([
                    'is_active' => false,
                ]);

                // Log audit trail
                AuditService::log('bulk_deactivate', "User {$user->name} deactivated in bulk operation", $user);

                $deactivatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully deactivated {$deactivatedCount} users",
            'deactivated_count' => $deactivatedCount
        ]);
    }

    /**
     * Bulk activate users.
     */
    public function bulkActivateUsers(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();
        $activatedCount = 0;

        foreach ($users as $user) {
            $user->update([
                'is_active' => true,
            ]);

            // Log audit trail
            AuditService::log('bulk_activate', "User {$user->name} activated in bulk operation", $user);

            $activatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully activated {$activatedCount} users",
            'activated_count' => $activatedCount
        ]);
    }
}
