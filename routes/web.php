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
        Route::get('/client/invoices', [App\Http\Controllers\ClientController::class, 'invoices'])->name('client.invoices');
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
        
        // Inspector management
        Route::get('/operations/inspectors', [App\Http\Controllers\OperationsController::class, 'inspectors'])->name('operations.inspectors');
        Route::get('/operations/inspectors/create', [App\Http\Controllers\OperationsController::class, 'createInspector'])->name('operations.inspectors.create');
        Route::post('/operations/inspectors', [App\Http\Controllers\OperationsController::class, 'storeInspector'])->name('operations.inspectors.store');
        Route::get('/operations/inspectors/{inspector}', [App\Http\Controllers\OperationsController::class, 'showInspector'])->name('operations.inspectors.show');
        
        // Service requests
        Route::get('/operations/service-requests', [App\Http\Controllers\OperationsController::class, 'serviceRequests'])->name('operations.service-requests');
        Route::get('/operations/service-requests/{serviceRequest}', [App\Http\Controllers\OperationsController::class, 'showServiceRequest'])->name('operations.service-requests.show');
        Route::put('/operations/service-requests/{serviceRequest}', [App\Http\Controllers\OperationsController::class, 'updateServiceRequest'])->name('operations.service-requests.update');
        Route::post('/operations/service-requests/{serviceRequest}/assign', [App\Http\Controllers\OperationsController::class, 'assignInspector'])->name('operations.service-requests.assign');
        Route::delete('/operations/service-requests/{serviceRequest}', [App\Http\Controllers\OperationsController::class, 'deleteServiceRequest'])->name('operations.service-requests.delete');
        
        // Reports
        Route::get('/operations/reports', [App\Http\Controllers\OperationsController::class, 'reports'])->name('operations.reports');
        Route::get('/operations/reports/{report}', [App\Http\Controllers\OperationsController::class, 'showReport'])->name('operations.reports.show');
        Route::post('/operations/reports/{report}/send-to-client', [App\Http\Controllers\OperationsController::class, 'sendReportToClient'])->name('operations.reports.send-to-client');
        
        // Invoices
        Route::get('/operations/invoices', [App\Http\Controllers\OperationsController::class, 'invoices'])->name('operations.invoices');
        Route::get('/operations/invoices/{invoice}', [App\Http\Controllers\OperationsController::class, 'showInvoice'])->name('operations.invoices.show');
        
        // Messages
        Route::get('/operations/messages', [App\Http\Controllers\OperationsController::class, 'messages'])->name('operations.messages');
        Route::get('/operations/messages/{message}', [App\Http\Controllers\OperationsController::class, 'showMessage'])->name('operations.messages.show');
        
        // Profile and Settings
        Route::get('/operations/profile', [App\Http\Controllers\OperationsController::class, 'profile'])->name('operations.profile');
        Route::put('/operations/profile', [App\Http\Controllers\OperationsController::class, 'updateProfile'])->name('operations.profile.update');
        Route::get('/operations/settings', [App\Http\Controllers\OperationsController::class, 'settings'])->name('operations.settings');
        Route::put('/operations/settings', [App\Http\Controllers\OperationsController::class, 'updateSettings'])->name('operations.settings.update');
    });
});
