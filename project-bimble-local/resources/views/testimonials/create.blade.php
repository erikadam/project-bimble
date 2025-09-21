<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Tambah Testimoni Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">
                    <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            {{-- Nama --}}
                            <div>
                                <x-input-label for="name" :value="__('Nama')" class="text-gray-300" />
                                {{-- PERBAIKAN DI SINI --}}
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200" :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            {{-- Jabatan --}}
                            <div>
                                <x-input-label for="title" :value="__('Jabatan (cth: Instruktur Profesional)')" class="text-gray-300" />
                                {{-- PERBAIKAN DI SINI --}}
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200" :value="old('title')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            {{-- Pesan --}}
                            <div>
                                <x-input-label for="message" :value="__('Pesan')" class="text-gray-300" />
                                <textarea name="message" id="message" rows="4" class="mt-1 block w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm text-gray-200" required>{{ old('message') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('message')" />
                            </div>

                            {{-- Gambar --}}
                            <div>
                                <x-input-label for="image" :value="__('Gambar')" class="text-gray-300" />
                                <input type="file" name="image" id="image" class="block mt-1 w-full text-sm text-gray-300 border border-gray-600 rounded-md cursor-pointer bg-gray-700 focus:outline-none file:bg-gray-600 file:text-gray-200 file:border-0 file:py-2 file:px-4" required>
                                <x-input-error class="mt-2" :messages="$errors->get('image')" />
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('testimonials.index') }}" class="text-sm text-gray-400 hover:text-gray-200 underline">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
