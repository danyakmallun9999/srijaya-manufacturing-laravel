<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'notes',
        // Product and company information
        'product_image',
        'company_logo',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'company_website',
        // Shipping information
        'shipping_address',
        'shipping_cost',
        'shipping_method',
        // Payment information
        'payment_method',
        'bank_name',
        'account_number',
        'account_holder',
        // Invoice customization
        'po_number',
        'seller_name',
        'terms_conditions',
        'notes_customer',
        // Payment tracking
        'paid_amount',
        'remaining_amount',
        'payment_date',
        // Invoice status tracking
        'payment_status',
        'is_revised',
        'revision_reason',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'payment_date' => 'date',
        'is_revised' => 'boolean',
    ];

    // Payment status constants
    const PAYMENT_STATUSES = [
        'Unpaid' => 'Unpaid',
        'Partial' => 'Partial Payment',
        'Paid' => 'Paid'
    ];

    // Payment method constants
    const PAYMENT_METHODS = [
        'transfer' => 'Transfer',
        'cash' => 'Cash',
        'transfer BCA' => 'Transfer BCA',
        'transfer BRI' => 'Transfer BRI',
        'transfer Mandiri' => 'Transfer Mandiri',
        'transfer paypal' => 'Transfer PayPal',
        'E-wallet' => 'E-Wallet'
    ];

    /**
     * Get the order that this invoice belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now() && $this->payment_status !== 'Paid';
    }

    /**
     * Get product image URL
     */
    public function getProductImageUrlAttribute()
    {
        // Priority 1: Use stored product_image from invoice
        if ($this->product_image) {
            return asset('storage/' . $this->product_image);
        }
        
        // Priority 2: Get from product model if it's a fixed product
        if ($this->order && $this->order->product_type === 'tetap' && $this->order->product && $this->order->product->image) {
            return asset('storage/' . $this->order->product->image);
        }
        
        // Priority 3: Fallback to order image if available
        if ($this->order && $this->order->image) {
            return asset('storage/' . $this->order->image);
        }
        
        return asset('images/no-image.svg');
    }

    /**
     * Get company logo URL
     */
    public function getCompanyLogoUrlAttribute()
    {
        if ($this->company_logo) {
            return asset('storage/' . $this->company_logo);
        }
        
        return asset('images/idefu.png');
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get payment status display name
     */
    public function getPaymentStatusDisplayAttribute()
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Calculate remaining amount
     */
    public function calculateRemainingAmount()
    {
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
        $this->payment_status = $this->remaining_amount <= 0 ? 'Paid' : 
                               ($this->paid_amount > 0 ? 'Partial' : 'Unpaid');
        $this->save();
    }

    /**
     * Update payment information
     */
    public function updatePayment($amount, $paymentMethod = null, $paymentDate = null)
    {
        $this->paid_amount += $amount;
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        if ($paymentDate) {
            $this->payment_date = $paymentDate;
        }
        $this->calculateRemainingAmount();
    }

    /**
     * Check if invoice can be revised
     */
    public function canBeRevised()
    {
        return $this->payment_status !== 'Paid' && !$this->is_revised;
    }

    /**
     * Revise invoice
     */
    public function revise($reason, $newData = [])
    {
        $this->is_revised = true;
        $this->revision_reason = $reason;
        $this->update($newData);
    }
}
