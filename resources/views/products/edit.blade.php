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
                    <form method="POST" action="{{ route('products.update', $product) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Nama Produk')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $product->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- <div class="mt-4">
                            <x-input-label for="bom_master" :value="__('BOM Master (Format JSON)')" />
                            <textarea id="bom_master" name="bom_master" rows="5"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bom_master', json_encode($product->bom_master, JSON_PRETTY_PRINT)) }}</textarea>
                            <x-input-error :messages="$errors->get('bom_master')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-600">Contoh format: [{"material_id": 1, "quantity": 5},
                                {"material_id": 3, "quantity": 2}]</p>
                            <p class="mt-1 text-sm text-gray-600">
                                ID Material yang tersedia:
                                @foreach ($materials as $material)
                                    <span class="font-semibold">{{ $material->id }}</span>={{ $material->name }};
                                @endforeach
                            </p>
                        </div> --}}

                        <div class="flex items-center justify-end mt-4">
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
