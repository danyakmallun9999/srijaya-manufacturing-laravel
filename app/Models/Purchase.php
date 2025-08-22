<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'material_id',
        'supplier',
        'quantity',
        'price',
    ];

    /**
     * Get the order that this purchase belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the material that was purchased.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}