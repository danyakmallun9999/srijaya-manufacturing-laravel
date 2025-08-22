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
    ];

    /**
     * Get the order that this income belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}