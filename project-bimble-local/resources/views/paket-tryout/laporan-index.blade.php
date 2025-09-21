<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Laporan Hasil Ujian Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{-- Form Filter dan Pencarian --}}
                    <form method="GET" action="{{ route('paket-tryout.laporan_index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            {{-- Filter Jenjang --}}
                            <div>
                                <label for="jenjang" class="block font-medium text-sm text-gray-300">Filter Jenjang</label>
                                <select name="jenjang" id="jenjang" onchange="this.form.submit()" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="">Semua Jenjang</option>
                                    <option value="SD" @selected(request('jenjang') == 'SD')>SD</option>
                                    <option value="SMP" @selected(request('jenjang') == 'SMP')>SMP</option>
                                    <option value="SMA" @selected(request('jenjang') == 'SMA')>SMA</option>
                                </select>
                            </div>

                            {{-- Pencarian Kode Soal --}}
                            <div class="md:col-span-2">
                                <label for="search" class="block font-medium text-sm text-gray-300">Cari Kode Paket</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="Masukkan kode paket..." class="flex-1 block w-full min-w-0 rounded-none rounded-l-md bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500">
                                    <button type="submit" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-600 bg-gray-700 text-gray-300 hover:bg-gray-600">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Paket Tryout --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-700 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Nama Paket
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-700 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-700 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paketTryouts as $paket)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        <p class="text-gray-200 whitespace-no-wrap">{{ $paket->nama_paket }}</p>
                                        <p class="text-gray-400 text-xs whitespace-no-wrap">{{ $paket->mataPelajaran->pluck('nama_mapel')->join(', ') }}</p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm font-mono bg-gray-900/50 rounded">{{ $paket->kode_soal }}</td>
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        <a href="{{ route('paket-tryout.responses', $paket->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            Lihat Respons
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-gray-500">
                                        Tidak ada paket tryout yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
