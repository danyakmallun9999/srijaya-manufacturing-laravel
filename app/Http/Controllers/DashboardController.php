<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Income;
use App\Models\Purchase;
use App\Models\ProductionCost;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Income::sum('amount'),
            'total_expenses' => Purchase::sum(DB::raw('quantity * price')) + ProductionCost::sum('amount'),
        ];

        $recentOrders = Order::with('customer')->latest()->take(5)->get();
        $recentInvoices = Invoice::with('order.customer')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentOrders', 'recentInvoices'));
    }
} 