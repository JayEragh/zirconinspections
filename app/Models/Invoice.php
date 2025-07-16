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
        'nhil_tax',
        'getfund_tax',
        'covid_tax',
        'subtotal',
        'total_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'nhil_tax' => 'decimal:2',
        'getfund_tax' => 'decimal:2',
        'covid_tax' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
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
        $this->subtotal = $this->amount;
        $this->total_amount = $this->subtotal + $this->nhil_tax + $this->getfund_tax + $this->covid_tax;
        
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
}
