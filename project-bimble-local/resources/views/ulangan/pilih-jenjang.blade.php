{{-- resources/views/ulangan/pilih-jenjang.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pilih Jenjang Pendidikan untuk Ulangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Silakan pilih jenjang pendidikan untuk melihat atau membuat ulangan.
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Kartu SD --}}
                        <a href="{{ route('ulangan.index', ['jenjang' => 'SD']) }}" class="block p-6 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg shadow transition">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">SD</h5>
                            <p class="font-normal text-gray-700 dark:text-gray-400">Lihat atau buat ulangan untuk tingkat Sekolah Dasar.</p>
                        </a>

                        {{-- Kartu SMP --}}
                        <a href="{{ route('ulangan.index', ['jenjang' => 'SMP']) }}" class="block p-6 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg shadow transition">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">SMP</h5>
                            <p class="font-normal text-gray-700 dark:text-gray-400">Lihat atau buat ulangan untuk tingkat Sekolah Menengah Pertama.</p>
                        </a>

                        {{-- Kartu SMA --}}
                        <a href="{{ route('ulangan.index', ['jenjang' => 'SMA']) }}" class="block p-6 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg shadow transition">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">SMA</h5>
                            <p class="font-normal text-gray-700 dark:text-gray-400">Lihat atau buat ulangan untuk tingkat Sekolah Menengah Atas.</p>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
