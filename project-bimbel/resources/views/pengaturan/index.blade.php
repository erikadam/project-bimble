<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Website') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-brand-dark-secondary overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Unggah Gambar Slider Baru
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Pilih gambar untuk ditambahkan ke slider halaman utama. Ukuran ideal adalah 1920x825 piksel.
                    </p>

                        <form action="{{ route('pengaturan.slider.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        <div class="flex items-center space-x-4">
            <div>
                <x-input-label for="image" :value="__('Unggah Gambar Baru')" />
                <input id="image" name="image" type="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                <x-input-error class="mt-2" :messages="$errors->get('image')" />
            </div>
            <x-primary-button class="mt-5">
                {{ __('Simpan') }}
            </x-primary-button>
        </div>
    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-brand-dark-secondary overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                     <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Galeri Slider Saat Ini
                    </h3>
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse ($sliderImages as $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Slider Image" class="rounded-lg object-cover aspect-video">
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form method="post" action="{{ route('pengaturan.slider.destroy', $image) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-white text-sm bg-red-600 hover:bg-red-700 px-3 py-1 rounded">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="col-span-full text-gray-500">Belum ada gambar slider yang diunggah.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
