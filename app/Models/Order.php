<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'product_type',
        'product_name',
        'product_specification',
        'order_date',
        'deadline',
        'quantity',
        'status',
        'total_price',
    ];

    /**
     * Get the customer for the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the material purchases for the order.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the other production costs for the order.
     */
    public function productionCosts()
    {
        return $this->hasMany(ProductionCost::class);
    }

    /**
     * Get the incomes/payments for the order.
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    /**
     * Get the invoices for the order.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the latest invoice for the order.
     */
    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class)->latestOfMany();
    }
}