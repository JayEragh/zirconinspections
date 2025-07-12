<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Report;
use App\Models\Message;
use App\Models\User;
use App\Models\Client;
use App\Models\Inspector;
use Illuminate\Support\Facades\Auth;

class InspectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:inspector');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        // Get assigned service requests
        $assignedRequests = ServiceRequest::where('inspector_id', $inspector->id)
            ->with(['client', 'reports'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get recent reports
        $recentReports = Report::where('inspector_id', $inspector->id)
            ->with(['serviceRequest', 'client'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get unread messages
        $unreadMessages = Message::where('recipient_id', $user->id)
            ->where('read', false)
            ->with(['sender', 'serviceRequest'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Statistics
        $totalRequests = ServiceRequest::where('inspector_id', $inspector->id)->count();
        $completedRequests = ServiceRequest::where('inspector_id', $inspector->id)
            ->where('status', 'completed')
            ->count();
        $pendingRequests = ServiceRequest::where('inspector_id', $inspector->id)
            ->where('status', 'in_progress')
            ->count();
        $totalReports = Report::where('inspector_id', $inspector->id)->count();
        
        return view('inspector.dashboard', compact(
            'assignedRequests',
            'recentReports',
            'unreadMessages',
            'totalRequests',
            'completedRequests',
            'pendingRequests',
            'totalReports'
        ));
    }

    public function serviceRequests()
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $serviceRequests = ServiceRequest::where('inspector_id', $inspector->id)
            ->with(['client', 'reports'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('inspector.service-requests', compact('serviceRequests'));
    }

    public function showServiceRequest($id)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $serviceRequest = ServiceRequest::where('id', $id)
            ->where('inspector_id', $inspector->id)
            ->with(['client', 'reports', 'inspector'])
            ->firstOrFail();
        
        return view('inspector.service-request-detail', compact('serviceRequest'));
    }

    public function updateServiceRequest(Request $request, $id)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $serviceRequest = ServiceRequest::where('id', $id)
            ->where('inspector_id', $inspector->id)
            ->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $serviceRequest->update([
            'status' => $request->status,
            'inspector_notes' => $request->notes,
        ]);
        
        return redirect()->back()->with('success', 'Service request updated successfully.');
    }

    public function reports()
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $reports = Report::where('inspector_id', $inspector->id)
            ->with(['serviceRequest', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('inspector.reports', compact('reports'));
    }

    public function showReport($id)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $report = Report::where('id', $id)
            ->where('inspector_id', $inspector->id)
            ->with(['serviceRequest', 'client', 'inspector'])
            ->firstOrFail();
        
        return view('inspector.report-detail', compact('report'));
    }

    public function createReport($serviceRequestId)
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
        
        return view('inspector.create-report', compact('serviceRequest'));
    }

    public function storeReport(Request $request, $serviceRequestId)
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'findings' => 'required|string',
            'recommendations' => 'required|string',
            'status' => 'required|in:draft,submitted,approved',
            'tank_number' => 'required|string|max:50',
            'product_gauge' => 'required|numeric|min:0',
            'h20_gauge' => 'required|numeric|min:0',
            'temperature' => 'required|numeric',
            'roof' => 'required|in:yes,no',
            'roof_weight' => 'nullable|numeric|min:0',
            'density' => 'required|numeric|min:0',
            'vcf' => 'required|numeric|min:0',
            'tov' => 'required|numeric|min:0',
            'water_vol' => 'required|numeric|min:0',
            'roof_vol' => 'nullable|numeric',
            'gov' => 'nullable|numeric',
            'gsv' => 'nullable|numeric',
            'mt_air' => 'nullable|numeric',
            'file_upload' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        $supportingFile = null;
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('reports', $fileName, 'public');
            $supportingFile = $fileName;
        }

        $report = Report::create([
            'service_request_id' => $serviceRequest->id,
            'inspector_id' => $inspector->id,
            'client_id' => $serviceRequest->client_id,
            'title' => $request->title,
            'content' => $request->content,
            'findings' => $request->findings,
            'recommendations' => $request->recommendations,
            'status' => $request->status,
            'inspection_date' => now()->toDateString(),
            'inspection_time' => now(),
            'tank_number' => $request->tank_number,
            'product_gauge' => $request->product_gauge,
            'water_gauge' => $request->h20_gauge,
            'temperature' => $request->temperature,
            'has_roof' => $request->roof === 'yes',
            'roof_weight' => $request->roof === 'yes' ? $request->roof_weight : null,
            'density' => $request->density,
            'vcf' => $request->vcf,
            'tov' => $request->tov,
            'water_volume' => $request->water_vol,
            'roof_volume' => $request->roof_vol,
            'gov' => $request->gov,
            'gsv' => $request->gsv,
            'mt_air' => $request->mt_air,
            'supporting_file' => $supportingFile,
        ]);
        
        // Update service request status if report is submitted
        if ($request->status === 'submitted') {
            $serviceRequest->update(['status' => 'completed']);
        }
        
        return redirect()->route('inspector.reports')->with('success', 'Report created successfully.');
    }

    public function editReport($id)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $report = Report::where('id', $id)
            ->where('inspector_id', $inspector->id)
            ->with(['serviceRequest', 'client', 'inspectionDataSets'])
            ->firstOrFail();
        
        return view('inspector.edit-report', compact('report'));
    }

    public function updateReport(Request $request, $id)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $report = Report::where('id', $id)
            ->where('inspector_id', $inspector->id)
            ->firstOrFail();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'findings' => 'required|string',
            'recommendations' => 'required|string',
            'status' => 'required|in:draft,submitted,approved',
            'data_sets' => 'required|array|min:1',
            'data_sets.*.inspection_date' => 'required|date',
            'data_sets.*.inspection_time' => 'required',
            'data_sets.*.tank_number' => 'required|string|max:50',
            'data_sets.*.product_gauge' => 'required|numeric|min:0',
            'data_sets.*.water_gauge' => 'required|numeric|min:0',
            'data_sets.*.temperature' => 'required|numeric',
            'data_sets.*.has_roof' => 'required|in:0,1',
            'data_sets.*.roof_weight' => 'nullable|numeric|min:0',
            'data_sets.*.density' => 'required|numeric|min:0',
            'data_sets.*.vcf' => 'required|numeric|min:0',
            'data_sets.*.tov' => 'required|numeric|min:0',
            'data_sets.*.water_volume' => 'required|numeric|min:0',
            'data_sets.*.roof_volume' => 'nullable|numeric',
            'data_sets.*.gov' => 'nullable|numeric',
            'data_sets.*.gsv' => 'nullable|numeric',
            'data_sets.*.mt_air' => 'nullable|numeric',
            'data_sets.*.notes' => 'nullable|string',
            'supporting_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        // Handle file upload
        $supportingFile = $report->supporting_file;
        if ($request->hasFile('supporting_file')) {
            $file = $request->file('supporting_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('reports', $fileName, 'public');
            $supportingFile = $fileName;
        }
        
        // Update report basic information
        $report->update([
            'title' => $request->title,
            'content' => $request->content,
            'findings' => $request->findings,
            'recommendations' => $request->recommendations,
            'status' => $request->status,
            'supporting_file' => $supportingFile,
        ]);

        // Clear existing data sets and create new ones
        $report->inspectionDataSets()->delete();

        // Create new data sets
        foreach ($request->data_sets as $dataSet) {
            $report->inspectionDataSets()->create([
                'inspection_date' => $dataSet['inspection_date'],
                'inspection_time' => $dataSet['inspection_time'],
                'tank_number' => $dataSet['tank_number'],
                'product_gauge' => $dataSet['product_gauge'],
                'water_gauge' => $dataSet['water_gauge'],
                'temperature' => $dataSet['temperature'],
                'has_roof' => $dataSet['has_roof'],
                'roof_weight' => $dataSet['roof_weight'] ?? null,
                'density' => $dataSet['density'],
                'vcf' => $dataSet['vcf'],
                'tov' => $dataSet['tov'],
                'water_volume' => $dataSet['water_volume'],
                'roof_volume' => $dataSet['roof_volume'] ?? null,
                'gov' => $dataSet['gov'] ?? null,
                'gsv' => $dataSet['gsv'] ?? null,
                'mt_air' => $dataSet['mt_air'] ?? null,
                'notes' => $dataSet['notes'] ?? null,
            ]);
        }
        
        return redirect()->route('inspector.reports')->with('success', 'Report updated successfully with ' . count($request->data_sets) . ' data sets.');
    }

    public function messages()
    {
        $user = Auth::user();
        
        $messages = Message::where('recipient_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'recipient', 'serviceRequest'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('inspector.messages', compact('messages'));
    }

    public function showMessage($id)
    {
        $user = Auth::user();
        
        $message = Message::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
            })
            ->with(['sender', 'recipient', 'serviceRequest'])
            ->firstOrFail();
        
        // Mark as read if recipient
        if ($message->recipient_id === $user->id && !$message->read) {
            $message->update(['read' => true]);
        }
        
        return view('inspector.message-detail', compact('message'));
    }

    public function createMessage()
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $clients = Client::orderBy('name')->get();
        $serviceRequests = ServiceRequest::where('inspector_id', $inspector->id)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('inspector.create-message', compact('clients', 'serviceRequests'));
    }

    public function storeMessage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'service_request_id' => 'nullable|exists:service_requests,id',
        ]);
        
        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'service_request_id' => $request->service_request_id,
        ]);
        
        return redirect()->route('inspector.messages')->with('success', 'Message sent successfully.');
    }

    public function profile()
    {
        $inspector = Auth::user();
        
        return view('inspector.profile', compact('inspector'));
    }

    public function updateProfile(Request $request)
    {
        $inspector = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $inspector->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $inspector->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function settings()
    {
        $user = Auth::user();
        
        return view('inspector.settings', compact('user'));
    }

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

    public function exportReportPDF($id)
    {
        $user = Auth::user();
        $inspector = Inspector::where('user_id', $user->id)->first();
        
        if (!$inspector) {
            abort(404, 'Inspector profile not found');
        }
        
        $report = Report::where('id', $id)
            ->where('inspector_id', $inspector->id)
            ->with(['serviceRequest', 'client', 'inspector'])
            ->firstOrFail();
        
        $pdf = \PDF::loadView('inspector.report-pdf', compact('report'));
        
        return $pdf->download('report-' . $report->id . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
