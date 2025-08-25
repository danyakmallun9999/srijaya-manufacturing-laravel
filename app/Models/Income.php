<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'type',
        'amount',
        'date',
        'payment_method',
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
     * Get the order that this income belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the payment method display name.
     */
    public function getPaymentMethodDisplayAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }
}