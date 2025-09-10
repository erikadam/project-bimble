
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Hasil Ujian Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Form Filter dan Pencarian --}}
                    <form method="GET" action="{{ route('paket-tryout.laporan_index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            {{-- Filter Jenjang --}}
                            <div>
                                <label for="jenjang" class="block font-medium text-sm text-gray-700">Filter Jenjang</label>
                                {{-- --- PERBAIKAN DI SINI --- --}}
                                <select name="jenjang" id="jenjang" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Semua Jenjang</option>
                                    <option value="SD" @selected(request('jenjang') == 'SD')>SD</option>
                                    <option value="SMP" @selected(request('jenjang') == 'SMP')>SMP</option>
                                    <option value="SMA" @selected(request('jenjang') == 'SMA')>SMA</option>
                                </select>
                            </div>
                            {{-- Cari Kode Soal --}}
                            <div>
                                <label for="search" class="block font-medium text-sm text-gray-700">Cari Kode Soal</label>
                                <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan kode soal..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                            {{-- Tombol Aksi --}}
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Hasil --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Paket</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paketTryouts as $paket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <p class="text-gray-900 font-semibold">{{ $paket->nama_paket }}</p>
                                        <p class="text-gray-600 text-xs">{{ $paket->mataPelajaran->first()->jenjang_pendidikan ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm font-mono bg-gray-100 rounded">{{ $paket->kode_soal }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        {{-- --- PERUBAHAN DI SINI --- --}}
                                        {{-- Tombol diubah menjadi "Lihat Respons" dan diarahkan ke halaman baru --}}
                                        <a href="{{ route('paket-tryout.responses', $paket->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
