<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Produk') }}
            </h2>
            {{-- <a href="{{ route('products.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Tambah Produk
            </a> --}}
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'fixed' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button @click="activeTab = 'fixed'"
                            :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'fixed', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'fixed' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Produk Tetap
                        </button>
                        <button @click="activeTab = 'custom'"
                            :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'custom', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'custom' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Produk Custom
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Fixed Products Tab -->
            <div x-show="activeTab === 'fixed'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Produk Tetap</h3>
                        <a href="{{ route('products.create') }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Tambah Produk
                        </a>
                    </div>

                    @if ($fixedProducts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gambar</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Produk</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Model</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis Kayu</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stok</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($fixedProducts as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                                    class="h-10 w-10 rounded-lg object-cover">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $product->description }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $product->model ?: '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $product->wood_type ?: '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $product->stock }}
                                                @if ($product->isFixed())
                                                    <button
                                                        onclick="openStockModal({{ $product->id }}, {{ $product->stock }})"
                                                        class="ml-2 text-indigo-600 hover:text-indigo-900 text-xs font-medium">
                                                        Update
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $fixedProducts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada produk tetap</p>
                            <p class="text-gray-400 text-sm mt-1">Tambahkan produk pertama untuk memulai</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Custom Products Tab -->
            <div x-show="activeTab === 'custom'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Produk Custom</h3>
                        <p class="text-sm text-gray-500">Produk custom hanya dapat ditambahkan saat membuat order</p>
                    </div>

                    @if ($customProducts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Gambar</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Produk</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No Order</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Spesifikasi</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($customProducts as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="{{ $order->image_url }}" alt="{{ $order->product_name }}"
                                                    class="h-10 w-10 rounded-lg object-cover">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $order->product_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->order_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $order->customer->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ Str::limit($order->product_specification, 50) ?: '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                                <button
                                                    onclick="openEditCustomModal({{ $order->id }}, '{{ $order->product_name }}', '{{ $order->product_specification ?? '' }}')"
                                                    class="text-emerald-600 hover:text-emerald-900">Edit</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $customProducts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada produk custom</p>
                            <p class="text-gray-400 text-sm mt-1">Produk custom akan muncul setelah membuat order
                                dengan produk custom</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Update Modal -->
    <div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stok Produk</h3>
                <form id="stockForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok Baru</label>
                        <input type="number" id="stock" name="stock" min="0"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStockModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Custom Product Modal -->
    <div id="editCustomModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Produk Custom</h3>
                <form id="editCustomForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="edit_product_name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                            Produk</label>
                        <input type="text" id="edit_product_name" name="product_name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="edit_product_specification"
                            class="block text-sm font-medium text-gray-700 mb-2">Spesifikasi</label>
                        <textarea id="edit_product_specification" name="product_specification" rows="3"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="edit_custom_image" class="block text-sm font-medium text-gray-700 mb-2">Gambar
                            Produk (Opsional)</label>
                        <input type="file" id="edit_custom_image" name="custom_image" accept="image/*"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-sm text-gray-500 mt-1">Format: jpeg, png, jpg, gif, webp. Maksimal 5MB</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditCustomModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openStockModal(productId, currentStock) {
            document.getElementById('stockModal').classList.remove('hidden');
            document.getElementById('stockForm').action = `/products/${productId}/stock`;
            document.getElementById('stock').value = currentStock;
        }

        function closeStockModal() {
            document.getElementById('stockModal').classList.add('hidden');
        }

        function openEditCustomModal(orderId, productName, productSpecification) {
            document.getElementById('editCustomModal').classList.remove('hidden');
            document.getElementById('editCustomForm').action = `/orders/${orderId}/update-custom-product`;
            document.getElementById('edit_product_name').value = productName;
            document.getElementById('edit_product_specification').value = productSpecification;
        }

        function closeEditCustomModal() {
            document.getElementById('editCustomModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
