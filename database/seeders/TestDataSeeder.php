<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\ServiceRequest;
use App\Models\Report;
use App\Models\Invoice;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create test users
        $client = User::create([
            'name' => 'John Client',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '555-0101',
            'address' => '123 Client Street, City, State 12345'
        ]);

        $inspector = User::create([
            'name' => 'Jane Inspector',
            'email' => 'inspector@example.com',
            'password' => Hash::make('password'),
            'role' => 'inspector',
            'phone' => '555-0202',
            'address' => '456 Inspector Avenue, City, State 12345'
        ]);

        $operations = User::create([
            'name' => 'Bob Operations',
            'email' => 'operations@example.com',
            'password' => Hash::make('password'),
            'role' => 'operations',
            'phone' => '555-0303',
            'address' => '789 Operations Blvd, City, State 12345'
        ]);

        // Create client record
        $clientRecord = Client::create([
            'user_id' => $client->id,
            'name' => 'ABC Company',
            'email' => 'info@abc.com',
            'phone' => '555-0101',
            'address' => '123 Business Street, City, State 12345',
            'contact_person' => 'John Client',
            'status' => 'active'
        ]);

        // Create inspector record
        $inspectorRecord = \App\Models\Inspector::create([
            'user_id' => $inspector->id,
            'name' => 'Jane Inspector',
            'email' => 'inspector@example.com',
            'phone' => '555-0202',
            'address' => '456 Inspector Avenue, City, State 12345',
            'status' => 'active'
        ]);

        // Create service requests
        $serviceRequest1 = ServiceRequest::create([
            'service_id' => 'SR-001',
            'client_id' => $clientRecord->id,
            'inspector_id' => $inspectorRecord->id,
            'depot' => 'Main Depot',
            'product' => 'Gasoline',
            'quantity_gsv' => 1000.00,
            'quantity_mt' => 750.00,
            'tank_numbers' => 'Tank 1, Tank 2',
            'service_type' => 'Quality Inspection',
            'specific_instructions' => 'Check for water content and temperature',
            'status' => 'in_progress',
            'assigned_at' => now()->subDays(2)
        ]);

        $serviceRequest2 = ServiceRequest::create([
            'service_id' => 'SR-002',
            'client_id' => $clientRecord->id,
            'inspector_id' => $inspectorRecord->id,
            'depot' => 'Secondary Depot',
            'product' => 'Diesel',
            'quantity_gsv' => 2000.00,
            'quantity_mt' => 1500.00,
            'tank_numbers' => 'Tank 3',
            'service_type' => 'Quantity Verification',
            'specific_instructions' => 'Verify quantity and check density',
            'status' => 'completed',
            'assigned_at' => now()->subDays(5),
            'completed_at' => now()->subDays(1)
        ]);

        $serviceRequest3 = ServiceRequest::create([
            'service_id' => 'SR-003',
            'client_id' => $clientRecord->id,
            'inspector_id' => $inspectorRecord->id,
            'depot' => 'Main Depot',
            'product' => 'Jet Fuel',
            'quantity_gsv' => 500.00,
            'quantity_mt' => 375.00,
            'tank_numbers' => 'Tank 4',
            'service_type' => 'Quality Inspection',
            'specific_instructions' => 'Check for contaminants',
            'status' => 'pending',
            'assigned_at' => now()->subDays(1)
        ]);

        // Create reports
        $report1 = Report::create([
            'service_request_id' => $serviceRequest2->id,
            'inspector_id' => $inspectorRecord->id,
            'client_id' => $clientRecord->id,
            'title' => 'Quality Inspection Report - Diesel Fuel',
            'content' => 'Comprehensive quality inspection completed for diesel fuel storage tanks.',
            'findings' => 'All quality parameters within acceptable limits. No contaminants detected.',
            'recommendations' => 'Continue with current storage practices. Monitor temperature regularly.',
            'status' => 'approved',
            'submitted_at' => now()->subDays(2),
            'approved_at' => now()->subDays(1)
        ]);

        $report2 = Report::create([
            'service_request_id' => $serviceRequest1->id,
            'inspector_id' => $inspectorRecord->id,
            'client_id' => $clientRecord->id,
            'title' => 'Quantity Verification Report - Gasoline',
            'content' => 'Quantity verification completed for gasoline storage tanks.',
            'findings' => 'Quantity measurements accurate. Minor temperature variations noted.',
            'recommendations' => 'Implement temperature monitoring system. Schedule regular inspections.',
            'status' => 'submitted',
            'submitted_at' => now()->subDays(1)
        ]);

        // Create invoices
        $invoice1 = Invoice::create([
            'invoice_number' => 'INV-001',
            'service_request_id' => $serviceRequest2->id,
            'client_id' => $clientRecord->id,
            'inspector_id' => $inspectorRecord->id,
            'amount' => 1500.00,
            'description' => 'Quality inspection services for diesel fuel storage tanks',
            'status' => 'paid',
            'due_date' => now()->subDays(10),
            'paid_at' => now()->subDays(5)
        ]);

        $invoice2 = Invoice::create([
            'invoice_number' => 'INV-002',
            'service_request_id' => $serviceRequest1->id,
            'client_id' => $clientRecord->id,
            'inspector_id' => $inspectorRecord->id,
            'amount' => 1200.00,
            'description' => 'Quantity verification services for gasoline storage tanks',
            'status' => 'sent',
            'due_date' => now()->addDays(15)
        ]);

        $invoice3 = Invoice::create([
            'invoice_number' => 'INV-003',
            'service_request_id' => $serviceRequest3->id,
            'client_id' => $clientRecord->id,
            'inspector_id' => $inspectorRecord->id,
            'amount' => 800.00,
            'description' => 'Quality inspection services for jet fuel storage tanks',
            'status' => 'sent',
            'due_date' => now()->addDays(30)
        ]);

        // Create messages
        Message::create([
            'sender_id' => $client->id,
            'recipient_id' => $inspector->id,
            'subject' => 'Service Request Update',
            'content' => 'Please provide an update on the gasoline inspection progress.',
            'service_request_id' => $serviceRequest1->id,
            'read' => false
        ]);

        Message::create([
            'sender_id' => $inspector->id,
            'recipient_id' => $client->id,
            'subject' => 'Inspection Completed',
            'content' => 'The diesel fuel inspection has been completed. Report submitted for review.',
            'service_request_id' => $serviceRequest2->id,
            'read' => true
        ]);

        Message::create([
            'sender_id' => $operations->id,
            'recipient_id' => $inspector->id,
            'subject' => 'New Assignment',
            'content' => 'You have been assigned to inspect the jet fuel storage tanks.',
            'service_request_id' => $serviceRequest3->id,
            'read' => false
        ]);

        $this->command->info('Test data created successfully!');
        $this->command->info('Test users:');
        $this->command->info('- Client: client@example.com / password');
        $this->command->info('- Inspector: inspector@example.com / password');
        $this->command->info('- Operations: operations@example.com / password');
    }
}
