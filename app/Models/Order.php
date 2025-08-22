<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'Draft',
        'Menunggu Produksi',
        'Dalam Produksi',
        'Selesai',
        'Dikirim',
        'Closed',
    ];

    public const PROGRESS_STATUSES = [
        'Draft',
        'Menunggu Produksi',
        'Dalam Produksi',
        'Selesai',
        'Dikirim',
        'Closed',
    ];

    public const STATUS_BADGE_CLASSES = [
        'Draft' => 'bg-gray-100 text-gray-800',
        'Menunggu Produksi' => 'bg-amber-100 text-amber-800',
        'Dalam Produksi' => 'bg-blue-100 text-blue-800',
        'Selesai' => 'bg-emerald-100 text-emerald-800',
        'Dikirim' => 'bg-indigo-100 text-indigo-800',
        'Closed' => 'bg-emerald-100 text-emerald-800',
    ];

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

    /**
     * Map status to progress index (0-based). Falls back to 0 for unknown statuses.
     */
    public static function getProgressIndex(string $status): int
    {
        $index = array_search($status, self::PROGRESS_STATUSES, true);
        return $index !== false ? (int) $index : 0;
    }

    /**
     * Whether invoice generation is allowed for this order.
     * Default policy: allowed when price is set and status is Selesai or Dikirim.
     */
    public function isInvoiceAllowed(): bool
    {
        if (!$this->total_price) {
            return false;
        }
        return in_array($this->status, ['Selesai', 'Dikirim'], true);
    }

    /**
     * Get CSS badge classes for current status.
     */
    public function getStatusBadgeClass(): string
    {
        return self::STATUS_BADGE_CLASSES[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}