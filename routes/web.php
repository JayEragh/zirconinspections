<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'sendContact'])->name('contact.send');

// Authentication routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.get');

// Client registration and routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Client routes
    Route::middleware(['role:client'])->group(function () {
        Route::get('/client/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('client.dashboard');
        Route::get('/client/service-requests', [App\Http\Controllers\ClientController::class, 'serviceRequests'])->name('client.service-requests');
        Route::get('/client/service-requests/create', [App\Http\Controllers\ClientController::class, 'createServiceRequest'])->name('client.service-requests.create');
        Route::post('/client/service-requests', [App\Http\Controllers\ClientController::class, 'storeServiceRequest'])->name('client.service-requests.store');
        Route::get('/client/service-requests/{serviceRequest}', [App\Http\Controllers\ClientController::class, 'showServiceRequest'])->name('client.service-requests.show');
        Route::get('/client/service-requests/{serviceRequest}/edit', [App\Http\Controllers\ClientController::class, 'editServiceRequest'])->name('client.service-requests.edit');
        Route::put('/client/service-requests/{serviceRequest}', [App\Http\Controllers\ClientController::class, 'updateServiceRequest'])->name('client.service-requests.update');
        Route::delete('/client/service-requests/{serviceRequest}', [App\Http\Controllers\ClientController::class, 'deleteServiceRequest'])->name('client.service-requests.delete');
        Route::get('/client/reports', [App\Http\Controllers\ClientController::class, 'reports'])->name('client.reports');
        Route::get('/client/reports/{report}', [App\Http\Controllers\ClientController::class, 'showReport'])->name('client.reports.show');
        Route::get('/client/reports/{report}/pdf', [App\Http\Controllers\ClientController::class, 'exportReportPDF'])->name('client.reports.pdf');
        Route::get('/client/reports/{report}/excel', [App\Http\Controllers\ClientController::class, 'exportReportExcel'])->name('client.reports.excel');
        Route::get('/client/outturn-reports', [App\Http\Controllers\ClientController::class, 'outturnReports'])->name('client.outturn-reports');
        Route::get('/client/outturn-reports/{outturnReport}', [App\Http\Controllers\ClientController::class, 'showOutturnReport'])->name('client.outturn-reports.show');
        Route::get('/client/outturn-reports/{outturnReport}/pdf', [App\Http\Controllers\ClientController::class, 'exportOutturnReportPDF'])->name('client.outturn-reports.pdf');
        Route::get('/client/invoices', [App\Http\Controllers\ClientController::class, 'invoices'])->name('client.invoices');
        Route::get('/client/invoices/{invoice}', [App\Http\Controllers\ClientController::class, 'showInvoice'])->name('client.invoices.show');
        Route::post('/client/invoices/{invoice}/payment-evidence', [App\Http\Controllers\ClientController::class, 'uploadPaymentEvidence'])->name('client.invoices.payment-evidence');
        Route::get('/client/messages', [App\Http\Controllers\ClientController::class, 'messages'])->name('client.messages');
        
        // Profile and Settings
        Route::get('/client/profile', [App\Http\Controllers\ClientController::class, 'profile'])->name('client.profile');
        Route::put('/client/profile', [App\Http\Controllers\ClientController::class, 'updateProfile'])->name('client.profile.update');
        Route::get('/client/settings', [App\Http\Controllers\ClientController::class, 'settings'])->name('client.settings');
        Route::put('/client/settings', [App\Http\Controllers\ClientController::class, 'updateSettings'])->name('client.settings.update');
    });

    // Inspector routes
    Route::middleware(['role:inspector'])->group(function () {
        Route::get('/inspector/dashboard', [App\Http\Controllers\InspectorController::class, 'dashboard'])->name('inspector.dashboard');
        
        // Service requests
        Route::get('/inspector/service-requests', [App\Http\Controllers\InspectorController::class, 'serviceRequests'])->name('inspector.service-requests');
        Route::get('/inspector/service-requests/{id}', [App\Http\Controllers\InspectorController::class, 'showServiceRequest'])->name('inspector.service-requests.show');
        Route::put('/inspector/service-requests/{id}', [App\Http\Controllers\InspectorController::class, 'updateServiceRequest'])->name('inspector.service-requests.update');
        
        // Reports
        Route::get('/inspector/reports', [App\Http\Controllers\InspectorController::class, 'reports'])->name('inspector.reports');
        Route::get('/inspector/reports/{id}', [App\Http\Controllers\InspectorController::class, 'showReport'])->name('inspector.reports.show');
        Route::get('/inspector/reports/create/{serviceRequestId}', [App\Http\Controllers\InspectorController::class, 'createReport'])->name('inspector.reports.create');
        Route::post('/inspector/reports/{serviceRequestId}', [App\Http\Controllers\InspectorController::class, 'storeReport'])->name('inspector.reports.store');
        Route::get('/inspector/reports/{id}/edit', [App\Http\Controllers\InspectorController::class, 'editReport'])->name('inspector.reports.edit');
        Route::put('/inspector/reports/{id}', [App\Http\Controllers\InspectorController::class, 'updateReport'])->name('inspector.reports.update');
        Route::get('/inspector/reports/{id}/pdf', [App\Http\Controllers\InspectorController::class, 'exportReportPDF'])->name('inspector.reports.pdf');
        
        // Outturn Reports
        Route::get('/inspector/outturn-reports', [App\Http\Controllers\InspectorOutturnController::class, 'outturnReports'])->name('inspector.outturn-reports');
        Route::get('/inspector/outturn-reports/create/{serviceRequestId}', [App\Http\Controllers\InspectorOutturnController::class, 'createOutturnReport'])->name('inspector.outturn-reports.create');
        Route::post('/inspector/outturn-reports/{serviceRequestId}', [App\Http\Controllers\InspectorOutturnController::class, 'storeOutturnReport'])->name('inspector.outturn-reports.store');
        Route::get('/inspector/outturn-reports/{outturnReport}', [App\Http\Controllers\InspectorOutturnController::class, 'showOutturnReport'])->name('inspector.outturn-reports.show');
        Route::get('/inspector/outturn-reports/{outturnReport}/pdf', [App\Http\Controllers\InspectorOutturnController::class, 'exportOutturnReportPDF'])->name('inspector.outturn-reports.pdf');
        
        // Messages
        Route::get('/inspector/messages', [App\Http\Controllers\InspectorController::class, 'messages'])->name('inspector.messages');
        Route::get('/inspector/messages/{id}', [App\Http\Controllers\InspectorController::class, 'showMessage'])->name('inspector.messages.show');
        Route::get('/inspector/messages/create', [App\Http\Controllers\InspectorController::class, 'createMessage'])->name('inspector.messages.create');
        Route::post('/inspector/messages', [App\Http\Controllers\InspectorController::class, 'storeMessage'])->name('inspector.messages.store');
        
        // Profile and Settings
        Route::get('/inspector/profile', [App\Http\Controllers\InspectorController::class, 'profile'])->name('inspector.profile');
        Route::put('/inspector/profile', [App\Http\Controllers\InspectorController::class, 'updateProfile'])->name('inspector.profile.update');
        Route::get('/inspector/settings', [App\Http\Controllers\InspectorController::class, 'settings'])->name('inspector.settings');
        Route::put('/inspector/settings', [App\Http\Controllers\InspectorController::class, 'updateSettings'])->name('inspector.settings.update');
    });

    // Operations routes
    Route::middleware(['role:operations'])->group(function () {
        Route::get('/operations/dashboard', [App\Http\Controllers\OperationsController::class, 'dashboard'])->name('operations.dashboard');
        
        // Client management
        Route::get('/operations/clients', [App\Http\Controllers\OperationsController::class, 'clients'])->name('operations.clients');
        Route::get('/operations/clients/{client}', [App\Http\Controllers\OperationsController::class, 'showClient'])->name('operations.clients.show');
        Route::get('/operations/clients/{client}/edit', [App\Http\Controllers\OperationsController::class, 'editClient'])->name('operations.clients.edit');
        Route::put('/operations/clients/{client}', [App\Http\Controllers\OperationsController::class, 'updateClient'])->name('operations.clients.update');
        Route::delete('/operations/clients/{client}', [App\Http\Controllers\OperationsController::class, 'deleteClient'])->name('operations.clients.delete');
        
        // User management
        Route::get('/operations/users', [App\Http\Controllers\OperationsController::class, 'users'])->name('operations.users');
        Route::get('/operations/users/create', [App\Http\Controllers\OperationsController::class, 'createUser'])->name('operations.users.create');
        Route::post('/operations/users', [App\Http\Controllers\OperationsController::class, 'storeUser'])->name('operations.users.store');
        Route::get('/operations/users/{user}', [App\Http\Controllers\OperationsController::class, 'showUser'])->name('operations.users.show');
        Route::get('/operations/users/{user}/edit', [App\Http\Controllers\OperationsController::class, 'editUser'])->name('operations.users.edit');
        Route::put('/operations/users/{user}', [App\Http\Controllers\OperationsController::class, 'updateUser'])->name('operations.users.update');
        Route::delete('/operations/users/{user}', [App\Http\Controllers\OperationsController::class, 'deleteUser'])->name('operations.users.delete');
        Route::post('/operations/users/{user}/toggle-status', [App\Http\Controllers\OperationsController::class, 'toggleUserStatus'])->name('operations.users.toggle-status');

        // Inspector management
        Route::get('/operations/inspectors', [App\Http\Controllers\OperationsController::class, 'inspectors'])->name('operations.inspectors');
        Route::get('/operations/inspectors/create', [App\Http\Controllers\OperationsController::class, 'createInspector'])->name('operations.inspectors.create');
        Route::post('/operations/inspectors', [App\Http\Controllers\OperationsController::class, 'storeInspector'])->name('operations.inspectors.store');
        Route::get('/operations/inspectors/{inspector}', [App\Http\Controllers\OperationsController::class, 'showInspector'])->name('operations.inspectors.show');
        Route::get('/operations/inspectors/{inspector}/edit', [App\Http\Controllers\OperationsController::class, 'editInspector'])->name('operations.inspectors.edit');
        Route::put('/operations/inspectors/{inspector}', [App\Http\Controllers\OperationsController::class, 'updateInspector'])->name('operations.inspectors.update');
        Route::delete('/operations/inspectors/{inspector}', [App\Http\Controllers\OperationsController::class, 'deleteInspector'])->name('operations.inspectors.delete');
        
        // Service requests
        Route::get('/operations/service-requests', [App\Http\Controllers\OperationsController::class, 'serviceRequests'])->name('operations.service-requests');
        Route::get('/operations/service-requests/{serviceRequest}', [App\Http\Controllers\OperationsController::class, 'showServiceRequest'])->name('operations.service-requests.show');
        Route::put('/operations/service-requests/{serviceRequest}', [App\Http\Controllers\OperationsController::class, 'updateServiceRequest'])->name('operations.service-requests.update');
        Route::post('/operations/service-requests/{serviceRequest}/assign', [App\Http\Controllers\OperationsController::class, 'assignInspector'])->name('operations.service-requests.assign');
        Route::delete('/operations/service-requests/{serviceRequest}', [App\Http\Controllers\OperationsController::class, 'deleteServiceRequest'])->name('operations.service-requests.delete');
        
        // Reports
        Route::get('/operations/reports', [App\Http\Controllers\OperationsController::class, 'reports'])->name('operations.reports');
        Route::get('/operations/reports/{report}', [App\Http\Controllers\OperationsController::class, 'showReport'])->name('operations.reports.show');
        Route::get('/operations/reports/{report}/pdf', [App\Http\Controllers\OperationsController::class, 'exportReportPDF'])->name('operations.reports.pdf');
        Route::get('/operations/reports/{report}/excel', [App\Http\Controllers\OperationsController::class, 'exportReportExcel'])->name('operations.reports.excel');
        Route::post('/operations/reports/{report}/approve', [App\Http\Controllers\OperationsController::class, 'approveReport'])->name('operations.reports.approve');
        Route::post('/operations/reports/{report}/decline', [App\Http\Controllers\OperationsController::class, 'declineReport'])->name('operations.reports.decline');
        Route::post('/operations/reports/{report}/send-to-client', [App\Http\Controllers\OperationsController::class, 'sendReportToClient'])->name('operations.reports.send-to-client');
        
        // Outturn Reports
        Route::get('/operations/outturn-reports', [App\Http\Controllers\OperationsController::class, 'outturnReports'])->name('operations.outturn-reports');
        Route::get('/operations/outturn-reports/{outturnReport}', [App\Http\Controllers\OperationsController::class, 'showOutturnReport'])->name('operations.outturn-reports.show');
        Route::get('/operations/outturn-reports/{outturnReport}/pdf', [App\Http\Controllers\OperationsController::class, 'exportOutturnReportPDF'])->name('operations.outturn-reports.pdf');
        Route::post('/operations/outturn-reports/{outturnReport}/approve', [App\Http\Controllers\OperationsController::class, 'approveOutturnReport'])->name('operations.outturn-reports.approve');
        Route::post('/operations/outturn-reports/{outturnReport}/decline', [App\Http\Controllers\OperationsController::class, 'declineOutturnReport'])->name('operations.outturn-reports.decline');
        Route::post('/operations/outturn-reports/{outturnReport}/send-to-client', [App\Http\Controllers\OperationsController::class, 'sendOutturnReportToClient'])->name('operations.outturn-reports.send-to-client');
        
        // Invoices
        Route::get('/operations/invoices', [App\Http\Controllers\OperationsController::class, 'invoices'])->name('operations.invoices');
        Route::get('/operations/invoices/create', [App\Http\Controllers\OperationsController::class, 'createInvoice'])->name('operations.invoices.create');
        Route::post('/operations/invoices', [App\Http\Controllers\OperationsController::class, 'storeInvoice'])->name('operations.invoices.store');
        Route::get('/operations/invoices/{invoice}', [App\Http\Controllers\OperationsController::class, 'showInvoice'])->name('operations.invoices.show');
        Route::get('/operations/invoices/{invoice}/edit', [App\Http\Controllers\OperationsController::class, 'editInvoice'])->name('operations.invoices.edit');
        Route::put('/operations/invoices/{invoice}', [App\Http\Controllers\OperationsController::class, 'updateInvoice'])->name('operations.invoices.update');
        Route::delete('/operations/invoices/{invoice}', [App\Http\Controllers\OperationsController::class, 'deleteInvoice'])->name('operations.invoices.delete');
        Route::post('/operations/invoices/{invoice}/mark-paid', [App\Http\Controllers\OperationsController::class, 'markAsPaid'])->name('operations.invoices.mark-paid');
        Route::post('/operations/invoices/{invoice}/approve', [App\Http\Controllers\OperationsController::class, 'approveInvoice'])->name('operations.invoices.approve');
        Route::post('/operations/invoices/{invoice}/undo-approval', [App\Http\Controllers\OperationsController::class, 'undoApproval'])->name('operations.invoices.undo-approval');
        Route::post('/operations/invoices/{invoice}/send-overdue-notification', [App\Http\Controllers\OperationsController::class, 'sendOverdueNotification'])->name('operations.invoices.send-overdue-notification');
        
        // Messages
        Route::get('/operations/messages', [App\Http\Controllers\OperationsController::class, 'messages'])->name('operations.messages');
        Route::get('/operations/messages/{message}', [App\Http\Controllers\OperationsController::class, 'showMessage'])->name('operations.messages.show');
        
        // Profile and Settings
        Route::get('/operations/profile', [App\Http\Controllers\OperationsController::class, 'profile'])->name('operations.profile');
        Route::put('/operations/profile', [App\Http\Controllers\OperationsController::class, 'updateProfile'])->name('operations.profile.update');
        Route::get('/operations/settings', [App\Http\Controllers\OperationsController::class, 'settings'])->name('operations.settings');
        Route::put('/operations/settings', [App\Http\Controllers\OperationsController::class, 'updateSettings'])->name('operations.settings.update');
        
        // Login logs export
        Route::get('/operations/login-logs/export', [App\Http\Controllers\OperationsController::class, 'exportLoginLogs'])->name('operations.login-logs.export');
        
        // Audit logs
        Route::get('/operations/audit-logs', [App\Http\Controllers\OperationsController::class, 'auditLogs'])->name('operations.audit-logs');
        
        // Bulk operations
        Route::post('/operations/bulk/assign-inspectors', [App\Http\Controllers\BulkOperationsController::class, 'bulkAssignInspectors'])->name('operations.bulk.assign-inspectors');
        Route::post('/operations/bulk/approve-reports', [App\Http\Controllers\BulkOperationsController::class, 'bulkApproveReports'])->name('operations.bulk.approve-reports');
        Route::post('/operations/bulk/decline-reports', [App\Http\Controllers\BulkOperationsController::class, 'bulkDeclineReports'])->name('operations.bulk.decline-reports');
        Route::post('/operations/bulk/send-to-clients', [App\Http\Controllers\BulkOperationsController::class, 'bulkSendToClients'])->name('operations.bulk.send-to-clients');
        Route::post('/operations/bulk/export-pdf', [App\Http\Controllers\BulkOperationsController::class, 'bulkExportPDF'])->name('operations.bulk.export-pdf');
        Route::post('/operations/bulk/export-excel', [App\Http\Controllers\BulkOperationsController::class, 'bulkExportExcel'])->name('operations.bulk.export-excel');
        Route::post('/operations/bulk/deactivate-users', [App\Http\Controllers\BulkOperationsController::class, 'bulkDeactivateUsers'])->name('operations.bulk.deactivate-users');
        Route::post('/operations/bulk/activate-users', [App\Http\Controllers\BulkOperationsController::class, 'bulkActivateUsers'])->name('operations.bulk.activate-users');
    });
});
