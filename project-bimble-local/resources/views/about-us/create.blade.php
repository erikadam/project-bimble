<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Tambah Informasi About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mengubah bg-white menjadi bg-gray-800 untuk tema gelap --}}
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('about-us.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Judul')" class="text-gray-300" />
                            <x-text-input id="title" class="block mt-1 w-full bg-gray-900 text-gray-200 border-gray-600" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" class="text-gray-300" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full bg-gray-900 text-gray-200 rounded-md shadow-sm border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Gambar (Rasio 4:3 direkomendasikan)')" class="text-gray-300" />
                            <input type="file" name="image" id="image" class="block mt-1 w-full text-gray-300 border-gray-600 rounded-md shadow-sm" required>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
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
