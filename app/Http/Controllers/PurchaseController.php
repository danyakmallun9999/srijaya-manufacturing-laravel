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

    public function storeMultiple(Request $request, Order $order)
    {
        $request->validate([
            'purchases' => 'required|array|min:1',
            'purchases.*.material_name' => 'required|string|max:255',
            'purchases.*.supplier' => 'nullable|string|max:255',
            'purchases.*.quantity' => 'required|string',
            'purchases.*.price' => 'required|string',
            'receipt_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $purchases = $request->input('purchases');
        $createdCount = 0;

        foreach ($purchases as $purchaseData) {
            // Skip empty rows
            if (empty($purchaseData['material_name']) || empty($purchaseData['quantity']) || empty($purchaseData['price'])) {
                continue;
            }

            // Convert formatted numbers back to integers
            $quantity = (float) str_replace(['.', ','], ['', ''], $purchaseData['quantity']);
            $price = (int) str_replace(['.', ','], ['', ''], $purchaseData['price']);

            $order->purchases()->create([
                'material_name' => $purchaseData['material_name'],
                'supplier' => $purchaseData['supplier'] ?? null,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            $createdCount++;
        }

        // Handle photo upload if provided
        if ($request->hasFile('receipt_photo')) {
            $photoPath = $request->file('receipt_photo')->store('receipts', 'public');
            // Add receipt photo to the last purchase
            if ($createdCount > 0) {
                $lastPurchase = $order->purchases()->latest()->first();
                $lastPurchase->update(['receipt_photo' => $photoPath]);
            }
        }

        $currentTab = $request->input('current_tab', 'pembelian');
        return redirect()->route('orders.show', $order)
            ->with('success', "Berhasil menambahkan {$createdCount} data pembelian material.")
            ->with('active_tab', $currentTab);
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