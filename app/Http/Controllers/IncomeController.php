<?php

// app/Http/Controllers/IncomeController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'type' => 'required|in:DP,Cicilan,Lunas',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $order->incomes()->create($validated);
        return redirect()->route('orders.show', $order)->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    public function destroy(Income $income)
    {
        $order = $income->order;
        $income->delete();
        return redirect()->route('orders.show', $order)->with('success', 'Data pemasukan berhasil dihapus.');
    }
}