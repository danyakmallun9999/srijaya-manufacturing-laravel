<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="product_category" value="tetap">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="name" :value="__('Nama Produk')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="old('name', $product->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="model" :value="__('Model')" />
                                    <x-text-input id="model" class="block mt-1 w-full" type="text" name="model"
                                        :value="old('model', $product->model)" />
                                    <x-input-error :messages="$errors->get('model')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="wood_type" :value="__('Jenis Kayu')" />
                                    <x-text-input id="wood_type" class="block mt-1 w-full" type="text" name="wood_type"
                                        :value="old('wood_type', $product->wood_type)" />
                                    <x-input-error :messages="$errors->get('wood_type')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="stock" :value="__('Stok')" />
                                    <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock"
                                        :value="old('stock', $product->stock)" min="0" />
                                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Gambar Produk')" />
                                    @if($product->image)
                                        <div class="mb-2">
                                            <img src="{{ $product->image_url }}" alt="Current Image" class="w-32 h-32 object-cover rounded-lg border">
                                        </div>
                                    @endif
                                    <input type="file" id="image" name="image" accept="image/*"
                                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <x-input-label for="details" :value="__('Detail Tambahan')" />
                            <textarea id="details" name="details"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" placeholder="Spesifikasi detail, ukuran, warna, dll">{{ old('details', $product->details) }}</textarea>
                            <x-input-error :messages="$errors->get('details')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
