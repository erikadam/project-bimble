<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Paket Demo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($paketTryouts as $paket)
                            <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-xl font-bold">{{ $paket->nama_paket }}</h4>
                                <p class="text-sm text-gray-500 mt-1">Tipe: {{ $paket->tipe_paket }}</p>
                                <p class="text-xs font-mono bg-gray-200 inline-block px-2 py-1 rounded-sm mt-3">Kode: {{ $paket->kode_soal }}</p>
                                <div class="mt-4 text-right">
                                    <a href="{{ route('demo.ujian.mulai', $paket->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                        Mulai Demo
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10 text-gray-500">
                                Belum ada paket tryout yang bisa didemokan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
