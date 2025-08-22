<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Invoice: <span class="font-mono">{{ $invoice->invoice_number }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                    <p>{{ session('info') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Invoice</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nomor Invoice:</p>
                                <p class="font-semibold">{{ $invoice->invoice_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Invoice:</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jatuh Tempo:</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status:</p>
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                    @if($invoice->status === 'Paid') bg-green-100 text-green-800
                                    @elseif($invoice->status === 'Overdue') bg-red-100 text-red-800
                                    @elseif($invoice->status === 'Sent') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $invoice->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Order</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Customer:</p>
                                <p class="font-semibold">{{ $invoice->order->customer->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Produk:</p>
                                <p class="font-semibold">{{ $invoice->order->product_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jumlah:</p>
                                <p class="font-semibold">{{ $invoice->order->quantity }} pcs</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Harga per Unit:</p>
                                <p class="font-semibold">Rp {{ number_format($invoice->order->total_price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-6">

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rincian Biaya</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between items-center py-2">
                            <span>Subtotal:</span>
                            <span class="font-semibold">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span>Pajak:</span>
                            <span class="font-semibold">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between items-center py-2 text-lg">
                            <span class="font-semibold">Total:</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    @if($invoice->status !== 'Paid')
                        <form action="{{ route('invoices.updateStatus', $invoice) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Sent">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Kirim Invoice
                            </button>
                        </form>

                        <form action="{{ route('invoices.updateStatus', $invoice) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Paid">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Tandai Lunas
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('invoices.download', $invoice) }}" 
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Download PDF
                    </a>

                    <a href="{{ route('orders.show', $invoice->order) }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali ke Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 