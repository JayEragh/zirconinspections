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
        Route::get('/client/reports', [App\Http\Controllers\ClientController::class, 'reports'])->name('client.reports');
        Route::get('/client/invoices', [App\Http\Controllers\ClientController::class, 'invoices'])->name('client.invoices');
        Route::get('/client/messages', [App\Http\Controllers\ClientController::class, 'messages'])->name('client.messages');
    });

    // Inspector routes
    Route::middleware(['role:inspector'])->group(function () {
        Route::get('/inspector/dashboard', [App\Http\Controllers\InspectorController::class, 'dashboard'])->name('inspector.dashboard');
        Route::get('/inspector/jobs', [App\Http\Controllers\InspectorController::class, 'jobs'])->name('inspector.jobs');
        Route::get('/inspector/jobs/{serviceRequest}', [App\Http\Controllers\InspectorController::class, 'showJob'])->name('inspector.jobs.show');
        Route::post('/inspector/jobs/{serviceRequest}/report', [App\Http\Controllers\InspectorController::class, 'submitReport'])->name('inspector.jobs.report');
        Route::get('/inspector/history', [App\Http\Controllers\InspectorController::class, 'history'])->name('inspector.history');
    });

    // Operations routes
    Route::middleware(['role:operations'])->group(function () {
        Route::get('/operations/dashboard', [App\Http\Controllers\OperationsController::class, 'dashboard'])->name('operations.dashboard');
        Route::resource('/operations/clients', App\Http\Controllers\OperationsController::class, ['as' => 'operations']);
        Route::resource('/operations/inspectors', App\Http\Controllers\OperationsController::class, ['as' => 'operations']);
        Route::resource('/operations/service-requests', App\Http\Controllers\ServiceRequestController::class, ['as' => 'operations']);
        Route::resource('/operations/reports', App\Http\Controllers\ReportController::class, ['as' => 'operations']);
        Route::resource('/operations/invoices', App\Http\Controllers\InvoiceController::class, ['as' => 'operations']);
        Route::post('/operations/service-requests/{serviceRequest}/assign', [App\Http\Controllers\OperationsController::class, 'assignInspector'])->name('operations.service-requests.assign');
        Route::post('/operations/reports/{report}/approve', [App\Http\Controllers\OperationsController::class, 'approveReport'])->name('operations.reports.approve');
    });
});
