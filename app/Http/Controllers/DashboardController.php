<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Income;
use App\Models\Purchase;
use App\Models\ProductionCost;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $period = $request->get('period', 'all_time'); // 1m|3m|6m|1y|all_time
        $mode = $request->get('mode', 'aggregate'); // aggregate|per_production

        $now = now();
        switch ($period) {
            case '1m':
                $startDate = $now->copy()->startOfMonth();
                break;
            case '3m':
                $startDate = $now->copy()->subMonths(2)->startOfMonth();
                break;
            case '6m':
                $startDate = $now->copy()->subMonths(5)->startOfMonth();
                break;
            case '1y':
                $startDate = $now->copy()->subMonths(11)->startOfMonth();
                break;
            case 'all_time':
            default:
                $startDate = null; // No start date limit for all time
                break;
        }
        $endDate = $now->copy()->endOfDay();

        // Stats in range
        $ordersQuery = Order::query();
        $incomeQuery = Income::query();
        $purchaseQuery = Purchase::query();
        $prodCostQuery = ProductionCost::query();

        if ($startDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $incomeQuery->whereBetween('date', [$startDate, $endDate]);
            $purchaseQuery->whereBetween('created_at', [$startDate, $endDate]);
            $prodCostQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalOrders = $ordersQuery->count();
        $totalRevenue = (float) $incomeQuery->sum('amount');
        $materialCosts = (float) $purchaseQuery->sum(DB::raw('quantity * price'));
        $prodCosts = (float) $prodCostQuery->sum('amount');
        $totalExpenses = $materialCosts + $prodCosts;

        // Per-production mode: tampilkan nilai rata-rata per order dalam periode
        $isPerProduction = $mode === 'per_production';
        $denominator = max($totalOrders, 1);

        $stats = [
            'total_orders' => $totalOrders,
            'total_revenue' => $isPerProduction ? ($totalRevenue / $denominator) : $totalRevenue,
            'total_expenses' => $isPerProduction ? ($totalExpenses / $denominator) : $totalExpenses,
            'is_per_production' => $isPerProduction,
            'period' => $period,
        ];

        $recentOrders = Order::with('customer')->latest()->take(5)->get();
        $recentInvoices = Invoice::with('order.customer')->latest()->take(5)->get();

        // Build months list covering selected period
        $monthsSpan = match ($period) {
            '1m' => 1,
            '3m' => 3,
            '6m' => 6,
            '1y' => 12,
            'all_time' => 12, // Show last 12 months for all time view
            default => 12,
        };
        
        $months = collect(range(0, $monthsSpan - 1))->reverse()->map(function ($i) use ($now) {
            $date = $now->copy()->subMonths($i);
            return [
                'key' => $date->format('Y-m'),
                'label' => $date->format('M Y'),
            ];
        });

        $revenueByMonth = $months->map(function ($m) {
            return (float) Income::where('date', 'like', $m['key'] . '%')->sum('amount');
        });
        $purchaseByMonth = $months->map(function ($m) {
            return (float) Purchase::where('created_at', 'like', $m['key'] . '%')->sum(DB::raw('quantity * price'));
        });
        $prodCostByMonth = $months->map(function ($m) {
            return (float) ProductionCost::where('created_at', 'like', $m['key'] . '%')->sum('amount');
        });
        $expensesByMonth = $purchaseByMonth->zip($prodCostByMonth)->map(fn($pair) => $pair[0] + $pair[1]);
        $ordersByMonth = $months->map(function ($m) {
            return (int) Order::where('created_at', 'like', $m['key'] . '%')->count();
        });

        // PERBAIKAN: Logika per-produksi yang lebih konsisten
        if ($isPerProduction) {
            // Untuk setiap bulan, hitung rata-rata per order
            $revenueByMonth = $revenueByMonth->values()->zip($ordersByMonth)->map(function($pair) {
                [$revenue, $orderCount] = $pair;
                // Jika tidak ada order di bulan tersebut, return 0
                return $orderCount > 0 ? $revenue / $orderCount : 0;
            });
            
            $expensesByMonth = $expensesByMonth->values()->zip($ordersByMonth)->map(function($pair) {
                [$expenses, $orderCount] = $pair;
                // Jika tidak ada order di bulan tersebut, return 0
                return $orderCount > 0 ? $expenses / $orderCount : 0;
            });
        }

        $charts = [
            'labels' => $months->pluck('label'),
            'revenue' => $revenueByMonth->values(), // Pastikan index di-reset
            'expenses' => $expensesByMonth->values(), // Pastikan index di-reset
            'orders' => $ordersByMonth->values(), // Pastikan index di-reset
            'is_per_production' => $isPerProduction,
        ];

        return view('dashboard', compact('stats', 'recentOrders', 'recentInvoices', 'charts', 'period', 'mode', 'startDate', 'endDate'));
    }
}