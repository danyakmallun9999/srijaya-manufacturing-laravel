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
        // Bersihkan format ribuan pada amount
        $amount = $request->input('amount');
        if ($amount) {
            $amount = str_replace('.', '', $amount);
            $request->merge(['amount' => $amount]);
        }
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

        // Update status order ke Closed jika pembayaran sudah lunas
        $totalIncomesBaru = $order->incomes()->sum('amount');
        if ($totalIncomesBaru >= $totalOrder && $totalOrder > 0) {
            $order->update(['status' => 'Closed']);
            // Tandai invoice (jika ada) sebagai Paid
            $latestInvoice = $order->latestInvoice;
            if ($latestInvoice) {
                $latestInvoice->update(['status' => 'Paid']);
            }
        }

        $currentTab = $request->input('current_tab', 'pemasukan');
        return redirect()->route('orders.show', $order)->with('success', 'Data pemasukan berhasil ditambahkan.')->with('active_tab', $currentTab);
    }

    public function destroy(Income $income, Request $request)
    {
        $order = $income->order;
        $income->delete();
        $currentTab = $request->input('current_tab', 'pemasukan');
        return redirect()->route('orders.show', $order)->with('success', 'Data pemasukan berhasil dihapus.')->with('active_tab', $currentTab);
    }
}