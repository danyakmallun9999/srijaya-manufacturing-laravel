<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Order Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6"
                            role="alert">
                            <strong class="font-bold">Oops! Ada yang salah dengan input Anda:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data" x-data="{ productType: '{{ old('product_type', 'tetap') }}' }">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div>
                                    <x-input-label for="customer_id" :value="__('Customer')" />
                                    <select name="customer_id" id="customer_id"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        required>
                                        <option value="">-- Pilih Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                @if (old('customer_id') == $customer->id) selected @endif>{{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="order_date" :value="__('Tanggal Order')" />
                                    <x-text-input id="order_date" class="block mt-1 w-full" type="date"
                                        name="order_date" :value="old('order_date', date('Y-m-d'))" required />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="deadline" :value="__('Deadline')" />
                                    <x-text-input id="deadline" class="block mt-1 w-full" type="date"
                                        name="deadline" :value="old('deadline')" />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="quantity" :value="__('Jumlah Pesanan')" />
                                    <x-text-input id="quantity" class="block mt-1 w-full" type="number"
                                        name="quantity" :value="old('quantity', 1)" required min="1" />
                                </div>
                            </div>

                            <div>
                                <div>
                                    <x-input-label :value="__('Jenis Produk')" />
                                    <div class="mt-2 space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="product_type" value="tetap"
                                                x-model="productType"
                                                class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                            <span class="ml-2">Produk Tetap</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="product_type" value="custom"
                                                x-model="productType"
                                                class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                            <span class="ml-2">Produk Custom</span>
                                        </label>
                                    </div>
                                </div>

                                <div x-show="productType === 'tetap'" x-transition class="mt-4">
                                    <x-input-label for="product_id" :value="__('Pilih Produk')" />
                                    <select name="product_id" id="product_id"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">-- Pilih Produk Tetap --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                @if (old('product_id') == $product->id) selected @endif>{{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="productType === 'custom'" x-transition class="mt-4 space-y-4">
                                    <div>
                                        <x-input-label for="product_name" :value="__('Nama Produk Custom')" />
                                        <x-text-input id="product_name" class="block mt-1 w-full" type="text"
                                            name="product_name" :value="old('product_name')" />
                                    </div>
                                    <div>
                                        <x-input-label for="custom_product_specification" :value="__('Spesifikasi')" />
                                        <textarea id="custom_product_specification" name="custom_product_specification" rows="4"
                                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Detail spesifikasi produk custom">{{ old('custom_product_specification') }}</textarea>
                                    </div>
                                    <div>
                                        <x-input-label for="custom_image" :value="__('Gambar Produk (Opsional)')" />
                                        <input type="file" id="custom_image" name="custom_image" accept="image/*"
                                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('custom_image') border-red-500 @enderror" />
                                        <p class="text-sm text-gray-500 mt-1">Upload gambar produk custom jika tersedia (Format: jpeg, png, jpg, gif, webp. Maksimal 5MB)</p>
                                        @error('custom_image')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div x-show="productType === 'tetap'" x-transition class="mt-4">
                                    <x-input-label for="fixed_product_specification" :value="__('Spesifikasi Tambahan (Opsional)')" />
                                    <textarea id="fixed_product_specification" name="fixed_product_specification" rows="4"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tambahkan spesifikasi khusus jika diperlukan">{{ old('fixed_product_specification') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('orders.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Buat Order') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
