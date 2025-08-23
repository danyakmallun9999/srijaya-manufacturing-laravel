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
            'amount' => 'required|string',
        ]);

        // Convert formatted number back to integer
        $validated['amount'] = (int) str_replace(['.', ','], ['', ''], $validated['amount']);

        $order->productionCosts()->create($validated);
        
        $currentTab = $request->input('current_tab', 'biaya');
        return redirect()->route('orders.show', $order)->with('success', 'Biaya produksi berhasil ditambahkan.')->with('active_tab', $currentTab);
    }

    // public function destroy(ProductionCost $productionCost)
    // {
    //     $order = $productionCost->order;
    //     $productionCost->delete();
    //     return redirect()->route('orders.show', $order)->with('success', 'Biaya produksi berhasil dihapus.');
    // }

    public function destroy(ProductionCost $productionCost, Request $request)
    {
        $order = $productionCost->order;
        $productionCost->delete();
        $currentTab = $request->input('current_tab', 'biaya');
        return redirect()->route('orders.show', $order)->with('success', 'Biaya produksi berhasil dihapus.')->with('active_tab', $currentTab);
    }
}