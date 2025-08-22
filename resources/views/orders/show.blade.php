<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Order: <span class="font-mono">{{ $order->order_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'info' }">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-4 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="#" @click.prevent="tab = 'info'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'info', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'info' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Info Order</a>
                    <a href="#" @click.prevent="tab = 'pembelian'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'pembelian', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'pembelian' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Pembelian</a>
                    <a href="#" @click.prevent="tab = 'biaya'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'biaya', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'biaya' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Biaya Produksi</a>
                    <a href="#" @click.prevent="tab = 'pemasukan'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'pemasukan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'pemasukan' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Pemasukan</a>
                    <a href="#" @click.prevent="tab = 'invoice'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'invoice', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'invoice' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Invoice</a>
                    <a href="#" @click.prevent="tab = 'ringkasan'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'ringkasan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'ringkasan' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Ringkasan</a>
                </nav>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div x-show="tab === 'info'">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Utama</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                            <p><strong>Produk:</strong> {{ $order->product_name }}</p>
                            <p><strong>Spesifikasi:</strong> {{ $order->product_specification ?: '-' }}</p>
                            <p><strong>Jumlah:</strong> {{ $order->quantity }} pcs</p>
                        </div>
                        <div>
                            <p><strong>Tanggal Order:</strong>
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
                            <p><strong>Deadline:</strong>
                                {{ $order->deadline ? \Carbon\Carbon::parse($order->deadline)->format('d M Y') : '-' }}
                            </p>
                            <p><strong>Status Saat Ini:</strong> <span
                                    class="font-semibold text-indigo-600">{{ $order->status }}</span></p>
                        </div>
                    </div>
                    <hr class="my-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status Order</h3>
                            <form action="{{ route('orders.updateStatus', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center space-x-4">
                                    <select name="status"
                                        class="block w-full md:w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        @foreach (['Draft', 'Menunggu Produksi', 'Dalam Produksi', 'Selesai', 'Dikirim', 'Lunas', 'Closed'] as $status)
                                            <option value="{{ $status }}"
                                                {{ $order->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <x-primary-button>Update</x-primary-button>
                                </div>
                            </form>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Harga Jual</h3>
                            <form action="{{ route('orders.updatePrice', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <x-input-label value="Harga per Unit" />
                                        <x-text-input type="number" name="total_price" 
                                            value="{{ $order->total_price }}" 
                                            class="block mt-1 w-full" step="100" />
                                    </div>
                                    <x-primary-button>Update</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'pembelian'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pembelian Material</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Material</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Supplier</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jumlah</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Harga</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Total</th>
                                        <th class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($order->purchases as $purchase)
                                        <tr>
                                            <td class="px-4 py-2">{{ $purchase->material_name }}</td>
                                            <td class="px-4 py-2">{{ $purchase->supplier }}</td>
                                            <td class="px-4 py-2">{{ $purchase->quantity }}</td>
                                            <td class="px-4 py-2">Rp
                                                {{ number_format($purchase->price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2">Rp
                                                {{ number_format($purchase->quantity * $purchase->price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pembelian material ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">Belum ada data pembelian.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($order->purchases->count() > 0)
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-lg font-semibold">
                                        Total Pembelian: Rp
                                        {{ number_format($order->purchases->sum(function ($purchase) {return $purchase->quantity * $purchase->price;}),0,',','.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pembelian</h3>
                            <form action="{{ route('purchases.store', $order) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <x-input-label value="Nama Material" />
                                    <x-text-input name="material_name" class="block mt-1 w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="Supplier" />
                                    <x-text-input type="text" name="supplier" class="block mt-1 w-full"
                                        required />
                                </div>
                                <div>
                                    <x-input-label value="Jumlah" />
                                    <x-text-input type="number" name="quantity" class="block mt-1 w-full"
                                        step="0.01" required />
                                </div>
                                <div>
                                    <x-input-label value="Harga per Unit" />
                                    <x-text-input type="number" name="price" class="block mt-1 w-full"
                                        step="100" required />
                                </div>
                                <div><x-primary-button>Tambah</x-primary-button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'biaya'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Biaya Produksi</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jenis Biaya</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Deskripsi</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($order->productionCosts as $cost)
                                        <tr>
                                            <td class="px-4 py-2">{{ $cost->type }}</td>
                                            <td class="px-4 py-2">{{ $cost->description ?: '-' }}</td>
                                            <td class="px-4 py-2">Rp {{ number_format($cost->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-gray-500">Belum ada
                                                data biaya produksi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($order->productionCosts->count() > 0)
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-lg font-semibold">
                                        Total Biaya Produksi: Rp
                                        {{ number_format($order->productionCosts->sum('amount'), 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Biaya Produksi</h3>
                            <form action="{{ route('costs.store', $order) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <x-input-label value="Jenis Biaya" />
                                    <select name="type"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Tenaga Kerja">Tenaga Kerja</option>
                                        <option value="Overhead">Overhead</option>
                                        <option value="Transportasi">Transportasi</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Deskripsi" />
                                    <x-text-input type="text" name="description" class="block mt-1 w-full" />
                                </div>
                                <div>
                                    <x-input-label value="Jumlah" />
                                    <x-text-input type="number" name="amount" class="block mt-1 w-full"
                                        step="100" required />
                                </div>
                                <div><x-primary-button>Tambah</x-primary-button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'pemasukan'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pemasukan</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jenis Pemasukan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($order->incomes as $income)
                                        <tr>
                                            <td class="px-4 py-2">{{ $income->type }}</td>
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($income->date)->format('d M Y') }}</td>
                                            <td class="px-4 py-2">Rp {{ number_format($income->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-gray-500">Belum ada
                                                data pemasukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($order->incomes->count() > 0)
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <p class="text-lg font-semibold">
                                        Total Pemasukan: Rp
                                        {{ number_format($order->incomes->sum('amount'), 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pemasukan</h3>
                            <form action="{{ route('incomes.store', $order) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <x-input-label value="Jenis Pemasukan" />
                                    <select name="type"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                        <option value="">-- Pilih --</option>
                                        <option value="DP">DP</option>
                                        <option value="Cicilan">Cicilan</option>
                                        <option value="Lunas">Lunas</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label value="Tanggal" />
                                    <x-text-input type="date" name="date" class="block mt-1 w-full" required />
                                </div>
                                <div>
                                    <x-input-label value="Jumlah" />
                                    <x-text-input type="number" name="amount" class="block mt-1 w-full"
                                        step="100" required />
                                </div>
                                <div>
                                    <p class="mb-2 text-sm text-gray-600">Sisa pembayaran: <span class="font-semibold text-red-600">Rp {{ number_format(($order->total_price ?? 0) * ($order->quantity ?? 1) - $order->incomes->sum('amount'), 0, ',', '.') }}</span></p>
                                </div>
                                <div><x-primary-button>Tambah</x-primary-button></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'invoice'">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Invoice</h3>
                            @if($order->invoices->count() > 0)
                                <div class="space-y-4">
                                    @foreach($order->invoices as $invoice)
                                        <div class="border rounded-lg p-4 bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-semibold text-lg">{{ $invoice->invoice_number }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                                                    </p>
                                                    <p class="text-sm text-gray-600">
                                                        Jatuh Tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
                                                    </p>
                                                    <p class="text-lg font-bold text-green-600">
                                                        Total: Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                                        @if($invoice->status === 'Paid') bg-green-100 text-green-800
                                                        @elseif($invoice->status === 'Overdue') bg-red-100 text-red-800
                                                        @elseif($invoice->status === 'Sent') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ $invoice->status }}
                                                    </span>
                                                    <div class="mt-2 space-x-2">
                                                        <a href="{{ route('invoices.show', $invoice) }}" 
                                                           class="text-blue-600 hover:text-blue-800 text-sm">Detail</a>
                                                        @if($invoice->status !== 'Paid')
                                                            <a href="{{ route('invoices.download', $invoice) }}" 
                                                               class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500 mb-4">Belum ada invoice untuk order ini.</p>
                                    @if($order->total_price && $order->status === 'Selesai')
                                        <form action="{{ route('invoices.generate', $order) }}" method="POST" class="inline">
                                            @csrf
                                            <x-primary-button>Generate Invoice</x-primary-button>
                                        </form>
                                    @else
                                        <p class="text-sm text-gray-400">
                                            Invoice dapat dibuat setelah order selesai dan harga jual ditentukan.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Invoice</h3>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-blue-900 mb-2">Cara Kerja Invoice:</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Invoice dibuat otomatis setelah order selesai</li>
                                    <li>• Nomor invoice: INV-YYYYMMDD-XXXX</li>
                                    <li>• Jatuh tempo default: 30 hari</li>
                                    <li>• Status: Draft → Sent → Paid</li>
                                    <li>• Invoice dapat didownload sebagai PDF</li>
                                </ul>
                            </div>
                            @if($order->total_price)
                                <div class="mt-4 bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-green-900 mb-2">Harga Jual:</h4>
                                    <p class="text-2xl font-bold text-green-800">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-green-700">
                                        Total: Rp {{ number_format($order->total_price * $order->quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'ringkasan'">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Keuangan & HPP</h3>
                    @php
                        $totalPembelian = $order->purchases->sum(function ($purchase) {
                            return $purchase->quantity * $purchase->price;
                        });
                        $totalBiayaProduksi = $order->productionCosts->sum('amount');
                        $totalHPP = $totalPembelian + $totalBiayaProduksi;
                        $totalHargaJual = ($order->total_price ?? 0) * ($order->quantity ?? 1);
                        $totalPemasukan = $order->incomes->sum('amount');
                        $totalMargin = $totalHargaJual - $totalHPP;
                        $sisaBayar = $totalHargaJual - $totalPemasukan;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-600">Total Pembelian Material</h4>
                            <p class="text-2xl font-bold text-blue-900">Rp {{ number_format($totalPembelian,0,',','.') }}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-red-600">Total Biaya Produksi Lain</h4>
                            <p class="text-2xl font-bold text-red-900">Rp {{ number_format($totalBiayaProduksi,0,',','.') }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-yellow-600">HPP (Total)</h4>
                            <p class="text-2xl font-bold text-yellow-900">Rp {{ number_format($totalHPP,0,',','.') }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-green-600">Total Harga Jual</h4>
                            <p class="text-2xl font-bold text-green-900">Rp {{ number_format($totalHargaJual,0,',','.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600">Total Pemasukan</h4>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPemasukan,0,',','.') }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-600">Sisa Pembayaran</h4>
                            <p class="text-2xl font-bold {{ $sisaBayar <= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($sisaBayar,0,',','.') }}</p>
                        </div>
                    </div>
                    <div class="bg-white border rounded-lg p-6 mt-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Analisis Margin, Laba/Rugi & Status</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Total Margin (Laba Kotor):</span>
                                <span class="font-semibold">Rp {{ number_format($totalMargin,0,',','.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Laba/Rugi (Profit/Loss):</span>
                                <span class="font-bold {{ $totalMargin > 0 ? 'text-green-600' : ($totalMargin < 0 ? 'text-red-600' : 'text-gray-600') }}">
                                    Rp {{ number_format($totalMargin,0,',','.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Status Order:</span>
                                @if($totalMargin > 0)
                                    <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">UNTUNG</span>
                                @elseif($totalMargin < 0)
                                    <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-800 text-sm font-semibold">RUGI</span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-sm font-semibold">IMPAS</span>
                                @endif
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="text-sm text-gray-500">
                            <p><b>HPP (Harga Pokok Produksi)</b> = Total Pembelian Material + Total Biaya Produksi Lain</p>
                            <p><b>Total Margin</b> = Total Harga Jual - HPP (Total)</p>
                            <p><b>Laba/Rugi</b> = Total Margin (positif = untung, negatif = rugi, nol = impas)</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
