<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'stock',
        'model',
        'wood_type',
        'details',
        'product_category',
        'bom_master',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bom_master' => 'array',
    ];

    /**
     * Get the orders for this product.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_name', 'name');
    }

    /**
     * Check if product has sufficient stock
     */
    public function hasStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Decrease stock
     */
    public function decreaseStock($quantity = 1)
    {
        if ($this->hasStock($quantity)) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Increase stock
     */
    public function increaseStock($quantity = 1)
    {
        $this->increment('stock', $quantity);
        return true;
    }

    /**
     * Check if product is fixed (not custom)
     */
    public function isFixed()
    {
        return $this->product_category === 'tetap';
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/no-image.svg');
    }
}
