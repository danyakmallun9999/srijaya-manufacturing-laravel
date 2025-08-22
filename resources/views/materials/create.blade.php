<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah material Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('materials.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama material')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="unit" :value="__('Unit')" />
                            <x-text-input id="unit" class="block mt-1 w-full" type="text" name="unit"
                                :value="old('unit')" />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="category" :value="__('Kategori')" />
                            <textarea id="category" name="category"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('category') }}</textarea>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('materials.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
