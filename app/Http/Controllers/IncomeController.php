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

        $totalIncomes = (float) $order->incomes()->sum('amount');
        $totalOrder = (float) (($order->total_price ?? 0) * ($order->quantity ?? 1));

        if ($totalOrder > 0) {
            $sisaBayar = $totalOrder - $totalIncomes;
            if ($validated['amount'] > $sisaBayar) {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Jumlah pemasukan melebihi sisa pembayaran!']);
            }
        }

        $order->incomes()->create($validated);

        // Update status order ke Lunas jika pembayaran sudah lunas
        $totalIncomesBaru = $order->incomes()->sum('amount');
        if ($totalIncomesBaru >= $totalOrder && $totalOrder > 0) {
            $order->update(['status' => 'Lunas']);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    public function destroy(Income $income)
    {
        $order = $income->order;
        $income->delete();
        return redirect()->route('orders.show', $order)->with('success', 'Data pemasukan berhasil dihapus.');
    }
}