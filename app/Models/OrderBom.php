<?php

// app/Models/OrderBom.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBom extends Model
{
    use HasFactory;

    // PENTING: Izinkan kolom-kolom ini untuk diisi secara massal
    protected $fillable = [
        'order_id',
        'material_id',
        'quantity',
    ];

    // Tambahkan ini untuk mempermudah pengambilan data nanti
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}