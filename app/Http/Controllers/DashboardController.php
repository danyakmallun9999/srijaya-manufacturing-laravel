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
        $period = $request->get('period', '3m'); // 1m|3m|6m|1y
        $mode = $request->get('mode', 'aggregate'); // aggregate|per_production

        $now = now();
        switch ($period) {
            case '1m':
                $startDate = $now->copy()->startOfMonth();
                break;
            case '6m':
                $startDate = $now->copy()->subMonths(5)->startOfMonth();
                break;
            case '1y':
                $startDate = $now->copy()->subMonths(11)->startOfMonth();
                break;
            case '3m':
            default:
                $startDate = $now->copy()->subMonths(2)->startOfMonth();
                break;
        }
        $endDate = $now->copy()->endOfDay();

        // Stats in range
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = (float) Income::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $materialCosts = (float) Purchase::whereBetween('created_at', [$startDate, $endDate])->sum(DB::raw('quantity * price'));
        $prodCosts = (float) ProductionCost::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
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
            '6m' => 6,
            '1y' => 12,
            default => 3,
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

        if ($isPerProduction) {
            $ordersCounts = $ordersByMonth->map(fn($c) => max($c, 1));
            $revenueByMonth = $revenueByMonth->values()->zip($ordersCounts)->map(fn($pair) => $pair[1] > 0 ? $pair[0] / $pair[1] : 0);
            $expensesByMonth = $expensesByMonth->values()->zip($ordersCounts)->map(fn($pair) => $pair[1] > 0 ? $pair[0] / $pair[1] : 0);
        }

        $charts = [
            'labels' => $months->pluck('label'),
            'revenue' => $revenueByMonth,
            'expenses' => $expensesByMonth,
            'orders' => $ordersByMonth,
            'is_per_production' => $isPerProduction,
        ];

        return view('dashboard', compact('stats', 'recentOrders', 'recentInvoices', 'charts', 'period', 'mode', 'startDate', 'endDate'));
    }
} 