<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                Dashboard
            </h2>
            <div class="mt-2 sm:mt-0 text-sm text-gray-500">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-6 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold mb-2">Selamat Datang Kembali!</h1>
                            <p class="text-blue-100 text-sm sm:text-base">Berikut adalah ringkasan bisnis Anda hari ini.
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <svg class="h-12 w-12 sm:h-16 sm:w-16 text-white opacity-80" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

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
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Pemasukan</p>
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
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Pengeluaran</p>
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
                                <p class="text-sm font-medium text-gray-500 mb-1">Profit</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-900">Rp
                                    {{ number_format(($stats['total_revenue'] ?? 0) - ($stats['total_expenses'] ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('orders.index') }}"
                        class="group flex items-center p-6 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 border border-blue-200 hover:border-blue-300">
                        <div class="flex-shrink-0">
                            <div
                                class="p-3 bg-blue-600 rounded-xl group-hover:bg-blue-700 transition-colors duration-200">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-blue-900 group-hover:text-blue-800">Kelola Order</h4>
                            <p class="text-sm text-blue-700">Lihat dan kelola semua pesanan</p>
                        </div>
                        <div class="ml-auto">
                            <svg class="h-5 w-5 text-blue-600 group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('customers.index') }}"
                        class="group flex items-center p-6 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-200 border border-green-200 hover:border-green-300">
                        <div class="flex-shrink-0">
                            <div
                                class="p-3 bg-green-600 rounded-xl group-hover:bg-green-700 transition-colors duration-200">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-green-900 group-hover:text-green-800">Kelola Customer</h4>
                            <p class="text-sm text-green-700">Lihat dan kelola data pelanggan</p>
                        </div>
                        <div class="ml-auto">
                            <svg class="h-5 w-5 text-green-600 group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('reports.index') }}"
                        class="group flex items-center p-6 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-200 border border-purple-200 hover:border-purple-300">
                        <div class="flex-shrink-0">
                            <div
                                class="p-3 bg-purple-600 rounded-xl group-hover:bg-purple-700 transition-colors duration-200">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-purple-900 group-hover:text-purple-800">Laporan</h4>
                            <p class="text-sm text-purple-700">Lihat laporan dan analisis bisnis</p>
                        </div>
                        <div class="ml-auto">
                            <svg class="h-5 w-5 text-purple-600 group-hover:translate-x-1 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Data -->
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
                                        <p class="text-sm font-semibold text-gray-900 mt-1">
                                            Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                        </p>
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
</x-app-layout>
