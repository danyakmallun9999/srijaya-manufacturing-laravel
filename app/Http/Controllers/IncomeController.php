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
            'payment_method' => 'nullable|in:transfer,cash,transfer BCA,transfer BRI,transfer Mandiri,transfer paypal,E-wallet',
        ]);

        $totalIncomes = (float) $order->incomes()->sum('amount');
        $totalOrder = (float) (($order->total_price ?? 0) * ($order->quantity ?? 1));

        // Hanya validasi sisa pembayaran jika harga jual sudah ditentukan
        if ($order->total_price && $order->total_price > 0 && $totalOrder > 0) {
            $sisaBayar = $totalOrder - $totalIncomes;
            if ($validated['amount'] > $sisaBayar) {
                return redirect()->back()->withInput()->withErrors(['amount' => 'Jumlah pemasukan melebihi sisa pembayaran!']);
            }
        }

        $order->incomes()->create($validated);

        // Hapus logika otomatis mengubah status order
        // Status order hanya diubah melalui menu Info Order

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