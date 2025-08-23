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
            'quantity' => 'required|string',
            'price' => 'required|string',
            'receipt_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convert formatted numbers back to integers
        $validated['quantity'] = (float) str_replace(['.', ','], ['', ''], $validated['quantity']);
        $validated['price'] = (int) str_replace(['.', ','], ['', ''], $validated['price']);

        // Handle photo upload
        if ($request->hasFile('receipt_photo')) {
            $photoPath = $request->file('receipt_photo')->store('receipts', 'public');
            $validated['receipt_photo'] = $photoPath;
        }

        $order->purchases()->create($validated);
        
        $currentTab = $request->input('current_tab', 'pembelian');
        return redirect()->route('orders.show', $order)->with('success', 'Data pembelian berhasil ditambahkan.')->with('active_tab', $currentTab);
    }

    public function uploadReceipt(Request $request, Order $order)
    {
        $request->validate([
            'receipt_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = $request->file('receipt_photo')->store('receipts', 'public');
        $order->purchases()->create([
            'material_name' => 'Nota',
            'quantity' => 1,
            'price' => 0,
            'receipt_photo' => $photoPath,
        ]);

        $currentTab = $request->input('current_tab', 'pembelian');
        return redirect()->route('orders.show', $order)->with('success', 'Foto nota berhasil diupload.')->with('active_tab', $currentTab);
    }

    public function destroy(Purchase $purchase, Request $request)
    {
        $order = $purchase->order;
        $purchase->delete();
        $currentTab = $request->input('current_tab', 'pembelian');
        return redirect()->route('orders.show', $order)->with('success', 'Data pembelian berhasil dihapus.')->with('active_tab', $currentTab);
    }
}