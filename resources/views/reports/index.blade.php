<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Laporan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Order</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                                <p class="text-sm text-gray-600">{{ $stats['orders_this_month'] }} bulan ini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Pemasukan</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600">Rp {{ number_format($stats['revenue_this_month'], 0, ',', '.') }} bulan ini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_expenses'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Invoice Pending</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_invoices'] }}</p>
                                <p class="text-sm text-red-600">{{ $stats['overdue_invoices'] }} overdue</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Data -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Terbaru</h3>
                        <div class="space-y-3">
                            @forelse($recentOrders as $order)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->product_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($order->status === 'Selesai') bg-green-100 text-green-800
                                            @elseif($order->status === 'Dalam Produksi') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->status }}
                                        </span>
                                        <p class="text-sm text-gray-600 mt-1">{{ $order->quantity }} pcs</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Belum ada order</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('reports.orders') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua Order →</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Terbaru</h3>
                        <div class="space-y-3">
                            @forelse($recentInvoices as $invoice)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $invoice->order->customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $invoice->order->product_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($invoice->status === 'Paid') bg-green-100 text-green-800
                                            @elseif($invoice->status === 'Overdue') bg-red-100 text-red-800
                                            @elseif($invoice->status === 'Sent') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $invoice->status }}
                                        </span>
                                        <p class="text-sm font-medium text-gray-900 mt-1">
                                            Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Belum ada invoice</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('reports.invoices') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua Invoice →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('reports.orders') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-blue-900">Laporan Order</span>
                        </a>
                        <a href="{{ route('reports.invoices') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <svg class="h-6 w-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-green-900">Laporan Invoice</span>
                        </a>
                        <a href="{{ route('reports.financial') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <svg class="h-6 w-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-purple-900">Laporan Keuangan</span>
                        </a>
                        <a href="{{ route('reports.customers') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                            <svg class="h-6 w-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-orange-900">Laporan Customer</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 