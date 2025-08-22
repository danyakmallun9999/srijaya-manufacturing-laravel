<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'material_name' => 'required|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
        ]);

        $order->purchases()->create($validated);
        return redirect()->route('orders.show', $order)->with('success', 'Data pembelian berhasil ditambahkan.');
    }

    public function destroy(Purchase $purchase)
    {
        $order = $purchase->order;
        $purchase->delete();
        return redirect()->route('orders.show', $order)->with('success', 'Data pembelian berhasil dihapus.');
    }
}