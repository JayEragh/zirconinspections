<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'service_request_id',
        'client_id',
        'amount',
        'description',
        'status',
        'due_date',
        'paid_at',
        'payment_evidence',
        'nhil_tax',
        'getfund_tax',
        'covid_tax',
        'vat',
        'subtotal',
        'total_amount',
        'approved_at',
        'sent_to_client_at',
        'payment_deadline',
        'overdue_notification_sent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'nhil_tax' => 'decimal:2',
        'getfund_tax' => 'decimal:2',
        'covid_tax' => 'decimal:2',
        'vat' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'approved_at' => 'datetime',
        'sent_to_client_at' => 'datetime',
        'payment_deadline' => 'date',
        'overdue_notification_sent' => 'boolean',
    ];

    /**
     * Get the service request that owns the invoice.
     */
    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the client that owns the invoice.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Generate a unique invoice number.
     */
    public static function generateInvoiceNumber()
    {
        do {
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }

    /**
     * Calculate taxes and total amount.
     */
    public function calculateTaxes()
    {
        $this->nhil_tax = $this->amount * 0.025; // 2.5%
        $this->getfund_tax = $this->amount * 0.025; // 2.5%
        $this->covid_tax = $this->amount * 0.01; // 1%
        $this->vat = $this->amount * 0.15; // 15%
        $this->subtotal = $this->amount;
        $this->total_amount = $this->subtotal + $this->nhil_tax + $this->getfund_tax + $this->covid_tax + $this->vat;
        
        return $this;
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute()
    {
        return 'GH₵ ' . number_format($this->amount, 2);
    }

    /**
     * Get formatted total amount with currency.
     */
    public function getFormattedTotalAttribute()
    {
        return 'GH₵ ' . number_format($this->total_amount, 2);
    }

    /**
     * Get formatted NHIL tax with currency.
     */
    public function getFormattedNhilTaxAttribute()
    {
        return 'GH₵ ' . number_format($this->nhil_tax, 2);
    }

    /**
     * Get formatted GETFUND tax with currency.
     */
    public function getFormattedGetfundTaxAttribute()
    {
        return 'GH₵ ' . number_format($this->getfund_tax, 2);
    }

    /**
     * Get formatted COVID tax with currency.
     */
    public function getFormattedCovidTaxAttribute()
    {
        return 'GH₵ ' . number_format($this->covid_tax, 2);
    }

    /**
     * Get formatted VAT with currency.
     */
    public function getFormattedVatAttribute()
    {
        return 'GH₵ ' . number_format($this->vat, 2);
    }

    /**
     * Approve the invoice and set payment deadline.
     */
    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'payment_deadline' => now()->addWeekdays(5), // 5 working days
        ]);
        
        return $this;
    }

    /**
     * Undo approval and revert to draft status.
     */
    public function undoApproval()
    {
        $this->update([
            'status' => 'draft',
            'approved_at' => null,
            'payment_deadline' => null,
            'sent_to_client_at' => null,
        ]);
        
        return $this;
    }

    /**
     * Send invoice to client.
     */
    public function sendToClient()
    {
        $this->update([
            'sent_to_client_at' => now(),
        ]);
        
        return $this;
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue()
    {
        return $this->payment_deadline && now()->isAfter($this->payment_deadline) && $this->status !== 'paid';
    }

    /**
     * Get days until payment deadline.
     */
    public function getDaysUntilDeadline()
    {
        if (!$this->payment_deadline) {
            return null;
        }
        
        return now()->diffInDays($this->payment_deadline, false);
    }

    /**
     * Get overdue days.
     */
    public function getOverdueDays()
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return now()->diffInDays($this->payment_deadline);
    }

    /**
     * Mark overdue notification as sent.
     */
    public function markOverdueNotificationSent()
    {
        $this->update(['overdue_notification_sent' => true]);
        
        return $this;
    }

    /**
     * Get status with overdue information.
     */
    public function getStatusWithOverdue()
    {
        if ($this->status === 'paid') {
            return 'Paid';
        }
        
        if ($this->isOverdue()) {
            return 'Overdue (' . $this->getOverdueDays() . ' days)';
        }
        
        if ($this->status === 'approved' && $this->payment_deadline) {
            $daysLeft = $this->getDaysUntilDeadline();
            if ($daysLeft > 0) {
                return 'Approved (' . $daysLeft . ' days left)';
            }
        }
        
        return ucfirst($this->status);
    }

    /**
     * Get payment evidence URL.
     */
    public function getPaymentEvidenceUrlAttribute()
    {
        if ($this->payment_evidence) {
            return asset('storage/' . $this->payment_evidence);
        }
        
        return null;
    }

    /**
     * Get payment evidence filename.
     */
    public function getPaymentEvidenceFilenameAttribute()
    {
        if ($this->payment_evidence) {
            return basename($this->payment_evidence);
        }
        
        return null;
    }
}
