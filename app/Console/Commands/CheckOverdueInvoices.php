<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class CheckOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue invoices and send notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for overdue invoices...');

        // Get all approved invoices that are overdue and haven't had notification sent
        $overdueInvoices = Invoice::with(['client.user', 'serviceRequest'])
            ->where('status', 'approved')
            ->where('payment_deadline', '<', now())
            ->where('overdue_notification_sent', false)
            ->get();

        if ($overdueInvoices->isEmpty()) {
            $this->info('No overdue invoices found.');
            return 0;
        }

        $this->info("Found {$overdueInvoices->count()} overdue invoice(s).");

        foreach ($overdueInvoices as $invoice) {
            $this->info("Processing overdue invoice: {$invoice->invoice_number}");

            // Send notification to client
            Message::create([
                'sender_id' => 1, // System user ID (you may need to adjust this)
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

            $this->info("Notification sent for invoice: {$invoice->invoice_number}");
        }

        $this->info('Overdue invoice check completed successfully.');
        return 0;
    }
}
