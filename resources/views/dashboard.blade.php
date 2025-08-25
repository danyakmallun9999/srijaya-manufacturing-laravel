<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                Dashboard
            </h2>

            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-2">
                <select name="period"
                    class="border-gray-300 rounded-lg text-sm pl-3 pr-10 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all_time" {{ ($period ?? 'all_time') === 'all_time' ? 'selected' : '' }}>Sepanjang Waktu</option>
                    <option value="1m" {{ ($period ?? 'all_time') === '1m' ? 'selected' : '' }}>1 Bulan</option>
                    <option value="3m" {{ ($period ?? 'all_time') === '3m' ? 'selected' : '' }}>Quartal (3 Bulan)</option>
                    <option value="6m" {{ ($period ?? 'all_time') === '6m' ? 'selected' : '' }}>6 Bulan</option>
                    <option value="1y" {{ ($period ?? 'all_time') === '1y' ? 'selected' : '' }}>1 Tahun</option>
                </select>

                <select name="mode"
                    class="border-gray-300 rounded-lg text-sm pl-3 pr-10 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="aggregate" {{ ($mode ?? 'aggregate') === 'aggregate' ? 'selected' : '' }}>Agregat
                    </option>
                    <option value="per_production" {{ ($mode ?? 'aggregate') === 'per_production' ? 'selected' : '' }}>
                        Per-Produksi (Rata-rata per Order)
                    </option>
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    Terapkan
                </button>
            </form>
        </div>

        <!-- Info periode -->
        <div class="mt-3 text-xs text-gray-500">
            @if($period === 'all_time')
                Periode: Sepanjang Waktu (12 bulan terakhir)
            @else
                Periode: {{ isset($startDate) ? $startDate->format('d M Y') : '-' }} s/d
                {{ isset($endDate) ? $endDate->format('d M Y') : '-' }}
            @endif
            @if ($stats['is_per_production'] ?? false)
                · Mode: Per-Produksi
            @else
                · Mode: Agregat
            @endif
        </div>
    </x-slot>
    <div class="py-6 sm:py-8 lg:py-12" x-data="dashboardData()" x-init="initCharts()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <!-- Total Orders Card -->
                <div
                    class="group bg-white hover:bg-gray-50 transition-colors duration-200 overflow-hidden shadow-sm hover:shadow-md rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-shrink-0">
                                <div
                                    class="p-3 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Order</p>
                                <p class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    {{ $stats['total_orders'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div
                    class="group bg-white hover:bg-gray-50 transition-colors duration-200 overflow-hidden shadow-sm hover:shadow-md rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-shrink-0">
                                <div
                                    class="p-3 bg-green-100 rounded-xl group-hover:bg-green-200 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500 mb-1">
                                    {{ $stats['is_per_production'] ?? false ? 'Rata-rata Pemasukan Order' : 'Total Pemasukan' }}
                                </p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp
                                    {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses Card -->
                <div
                    class="group bg-white hover:bg-gray-50 transition-colors duration-200 overflow-hidden shadow-sm hover:shadow-md rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-shrink-0">
                                <div
                                    class="p-3 bg-red-100 rounded-xl group-hover:bg-red-200 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500 mb-1">
                                    {{ $stats['is_per_production'] ?? false ? 'Rata-rata Pengeluaran Order' : 'Total Pengeluaran' }}
                                </p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp
                                    {{ number_format($stats['total_expenses'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profit Card -->
                <div
                    class="group bg-white hover:bg-gray-50 transition-colors duration-200 overflow-hidden shadow-sm hover:shadow-md rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-shrink-0">
                                <div
                                    class="p-3 bg-yellow-100 rounded-xl group-hover:bg-yellow-200 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-500 mb-1">
                                    {{ $stats['is_per_production'] ?? false ? 'Rata-rata Profit per Order' : 'Total Profit' }}
                                </p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp
                                    {{ number_format(($stats['total_revenue'] ?? 0) - ($stats['total_expenses'] ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts with Loading -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                <!-- Finance Chart -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 lg:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">
                        {{ $charts['is_per_production'] ?? false ? 'Rata-rata Pemasukan vs Pengeluaran per Order' : 'Pemasukan vs Pengeluaran (Total)' }}
                    </h3>
                    <div class="relative">
                        <!-- Loading State -->
                        <div x-show="!financeChartReady"
                            class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">Memuat chart...</p>
                            </div>
                        </div>
                        <!-- Chart Canvas -->
                        <canvas id="financeTrendChart" height="110" x-show="financeChartReady"
                            style="display: none;"></canvas>
                    </div>
                </div>

                <!-- Orders Chart -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Jumlah Order</h3>
                    <div class="relative">
                        <!-- Loading State -->
                        <div x-show="!ordersChartReady"
                            class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">Memuat chart...</p>
                            </div>
                        </div>
                        <!-- Chart Canvas -->
                        <canvas id="ordersTrendChart" height="110" x-show="ordersChartReady"
                            style="display: none;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Data (existing code remains the same) -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Order Terbaru</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Real-time
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentOrders ?? [] as $order)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 border border-gray-100">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="h-5 w-5 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-600">{{ $order->customer->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->product_name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            @if ($order->status === 'Selesai') bg-green-100 text-green-800
                                            @elseif($order->status === 'Dalam Produksi') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                        <p class="text-sm text-gray-600 mt-1 font-medium">{{ $order->quantity }} pcs
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm mt-2">Belum ada order</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('orders.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Lihat Semua Order
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Invoice Terbaru</h3>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Terbaru
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentInvoices ?? [] as $invoice)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 border border-gray-100">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="h-5 w-5 text-green-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $invoice->invoice_number }}</p>
                                            <p class="text-sm text-gray-600">{{ $invoice->order->customer->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $invoice->order->product_name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            class="inline-flex px-3 py-1 text-xs font-semibold rounded-full
                                            @if ($invoice->status === 'Paid') bg-green-100 text-green-800
                                            @elseif($invoice->status === 'Overdue') bg-red-100 text-red-800
                                            @elseif($invoice->status === 'Sent') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $invoice->status }}
                                        </span>
                                        <p class="text-sm font-semibold text-gray-900 mt-1">Rp
                                            {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm mt-2">Belum ada invoice</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('reports.invoices') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                Lihat Semua Invoice
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function dashboardData() {
            return {
                financeChartReady: false,
                ordersChartReady: false,

                initCharts() {
                    // Simulasi delay untuk memastikan DOM siap
                    setTimeout(() => {
                        this.renderFinanceChart();
                        this.renderOrdersChart();
                    }, 100);
                },

                renderFinanceChart() {
                    const labels = @json($charts['labels'] ?? []);
                    const revenue = @json($charts['revenue'] ?? []);
                    const expenses = @json($charts['expenses'] ?? []);
                    const isPerProduction = @json($charts['is_per_production'] ?? false);

                    // DEBUG: Lihat data yang masuk
                    console.log('Raw Data:', {
                        labels,
                        revenue,
                        expenses
                    });

                    const financeCtx = document.getElementById('financeTrendChart');
                    if (financeCtx && window.Chart) {
                        // Proses data dengan lebih hati-hati
                        const processedRevenue = revenue.map((val, index) => {
                            let numVal = parseFloat(val);
                            if (isNaN(numVal) || numVal < 0) numVal = 0;
                            console.log(`Revenue[${index}]:`, val, '->', numVal);
                            return numVal;
                        });

                        const processedExpenses = expenses.map((val, index) => {
                            let numVal = parseFloat(val);
                            if (isNaN(numVal) || numVal < 0) numVal = 0;
                            console.log(`Expenses[${index}]:`, val, '->', numVal);
                            return numVal;
                        });

                        console.log('Processed Data:', {
                            processedRevenue,
                            processedExpenses
                        });

                        new Chart(financeCtx, {
                            type: 'line',
                            data: {
                                labels,
                                datasets: [{
                                    label: isPerProduction ? 'Pemasukan / Order' : 'Pemasukan',
                                    data: processedRevenue,
                                    borderColor: '#22c55e',
                                    backgroundColor: 'transparent',
                                    borderWidth: 2,
                                    tension: 0, // UBAH KE 0 DULU untuk test linear
                                    fill: false,
                                    pointBackgroundColor: '#22c55e',
                                    pointBorderColor: '#22c55e',
                                    pointBorderWidth: 1,
                                    pointRadius: 3,
                                    pointHoverRadius: 5,
                                }, {
                                    label: isPerProduction ? 'Pengeluaran / Order' : 'Pengeluaran',
                                    data: processedExpenses,
                                    borderColor: '#ef4444',
                                    backgroundColor: 'transparent',
                                    borderWidth: 2,
                                    tension: 0, // UBAH KE 0 DULU untuk test linear
                                    fill: false,
                                    pointBackgroundColor: '#ef4444',
                                    pointBorderColor: '#ef4444',
                                    pointBorderWidth: 1,
                                    pointRadius: 3,
                                    pointHoverRadius: 5,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        display: true,
                                        grid: {
                                            display: true,
                                            color: 'rgba(0,0,0,0.1)'
                                        }
                                    },
                                    y: {
                                        display: true,
                                        beginAtZero: true,
                                        grid: {
                                            display: true,
                                            color: 'rgba(0,0,0,0.1)'
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: true
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.label + ': Rp ' + context.parsed.y
                                                    .toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // Chart selesai dibuat, tampilkan
                        this.financeChartReady = true;
                    }
                },

                renderOrdersChart() {
                    const labels = @json($charts['labels'] ?? []);
                    const orders = @json($charts['orders'] ?? []);

                    const ordersCtx = document.getElementById('ordersTrendChart');
                    if (ordersCtx && window.Chart) {
                        new Chart(ordersCtx, {
                            type: 'bar',
                            data: {
                                labels,
                                datasets: [{
                                    label: 'Order',
                                    data: orders,
                                    backgroundColor: 'rgba(59,130,246,0.3)',
                                    borderColor: '#3b82f6',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        stepSize: 1, // Menampilkan angka bulat 1, 2, 3, dst
                                        ticks: {
                                            precision: 0 // Menghilangkan desimal
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });

                        // Chart selesai dibuat, tampilkan
                        this.ordersChartReady = true;
                    }
                }
            }
        }
    </script>
</x-app-layout>
