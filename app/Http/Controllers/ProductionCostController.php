<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionCost;
use Illuminate\Http\Request;

class ProductionCostController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $order->productionCosts()->create($validated);
        return redirect()->route('orders.show', $order)->with('success', 'Biaya produksi berhasil ditambahkan.');
    }

    // public function destroy(ProductionCost $productionCost)
    // {
    //     $order = $productionCost->order;
    //     $productionCost->delete();
    //     return redirect()->route('orders.show', $order)->with('success', 'Biaya produksi berhasil dihapus.');
    // }

    public function destroy(ProductionCost $productionCost)
    {
        $order = $productionCost->order;
        $productionCost->delete();
        return redirect()->route('orders.show', $order)->with('success', 'Biaya produksi berhasil dihapus.');
    }
}