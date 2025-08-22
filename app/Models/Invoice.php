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
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the order that this invoice belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the payments for this invoice.
     */
    public function payments()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Calculate remaining amount to be paid
     */
    public function getRemainingAmountAttribute()
    {
        $paidAmount = $this->payments->sum('amount');
        return $this->total_amount - $paidAmount;
    }

    /**
     * Check if invoice is fully paid
     */
    public function isPaid()
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now() && !$this->isPaid();
    }
}
