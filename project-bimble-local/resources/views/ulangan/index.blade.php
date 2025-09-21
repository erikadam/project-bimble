<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            {{-- JUDUL HALAMAN --}}
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4 sm:mb-0">
                Manajemen Ulangan
                @if($jenjang)
                    <span class="text-gray-500 dark:text-gray-400">| Jenjang</span>
                    <span class="text-indigo-500 dark:text-indigo-400 font-bold">{{ $jenjang }}</span>
                @endif
            </h2>

            {{-- TOMBOL TAMBAH ULANGAN --}}
            @if ($jenjang)
                <a href="{{ route('ulangan.create', ['jenjang' => $jenjang]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Ulangan
                </a>
            @else
                 <p class="text-sm text-gray-500 dark:text-gray-400">Pilih jenjang terlebih dahulu untuk menambah ulangan baru.</p>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- FILTER JENJANG --}}
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Berdasarkan Jenjang:</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('ulangan.index') }}" class="px-4 py-2 text-sm font-medium rounded-md transition {{ !$jenjang ? 'bg-indigo-600 text-white shadow' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">Semua</a>
                    <a href="{{ route('ulangan.index', ['jenjang' => 'SD']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $jenjang == 'SD' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">SD</a>
                    <a href="{{ route('ulangan.index', ['jenjang' => 'SMP']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $jenjang == 'SMP' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">SMP</a>
                    <a href="{{ route('ulangan.index', ['jenjang' => 'SMA']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition {{ $jenjang == 'SMA' ? 'bg-indigo-600 text-white shadow' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">SMA</a>
                </div>
            </div>

            {{-- KONTEN TABEL --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @include('ulangan.partials.ulangan-table')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
