<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\ProductionCost;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Dashboard with summary reports
     */
    public function index()
    {
        $currentMonth = now()->format('Y-m');
        
        // Summary statistics
        $stats = [
            'total_orders' => Order::count(),
            'orders_this_month' => Order::where('created_at', 'like', $currentMonth . '%')->count(),
            'total_revenue' => Income::sum('amount'),
            'revenue_this_month' => Income::where('date', 'like', $currentMonth . '%')->sum('amount'),
            'total_expenses' => Purchase::sum(DB::raw('quantity * price')) + ProductionCost::sum('amount'),
            'pending_invoices' => Invoice::where('status', '!=', 'Paid')->count(),
            'overdue_invoices' => Invoice::where('due_date', '<', now())->where('status', '!=', 'Paid')->count(),
        ];

        // Recent orders
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Recent invoices
        $recentInvoices = Invoice::with('order.customer')
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue chart data
        $monthlyRevenue = Income::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as total')
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.index', compact('stats', 'recentOrders', 'recentInvoices', 'monthlyRevenue'));
    }

    /**
     * Order report
     */
    public function orders(Request $request)
    {
        $query = Order::with('customer');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('order_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('order_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $orders = $query->latest()->paginate(20);
        $customers = Customer::orderBy('name')->get();

        return view('reports.orders', compact('orders', 'customers'));
    }

    /**
     * Invoice report
     */
    public function invoices(Request $request)
    {
        $query = Invoice::with('order.customer');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('invoice_date', '<=', $request->end_date);
        }

        $invoices = $query->latest()->paginate(20);

        return view('reports.invoices', compact('invoices'));
    }

    /**
     * Financial report
     */
    public function financial(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Revenue
        $revenue = Income::whereBetween('date', [$startDate, $endDate])->sum('amount');

        // Expenses
        $materialCosts = Purchase::whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('quantity * price'));
        $productionCosts = ProductionCost::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        $totalExpenses = $materialCosts + $productionCosts;

        // Profit
        $profit = $revenue - $totalExpenses;

        // Monthly breakdown
        $monthlyData = Income::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as revenue')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('reports.financial', compact('revenue', 'totalExpenses', 'profit', 'monthlyData', 'startDate', 'endDate'));
    }

    /**
     * Customer report
     */
    public function customers(Request $request)
    {
        $query = Customer::withCount('orders')
            ->withSum('orders', 'total_price');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $customers = $query->latest()->paginate(20);

        return view('reports.customers', compact('customers'));
    }

    /**
     * Export report to Excel/PDF
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'orders');
        $format = $request->get('format', 'excel');

        // TODO: Implement export functionality
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
