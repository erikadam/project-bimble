<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Analisis Butir Soal
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $ulangan->nama_ulangan }}
                </p>
            </div>
             <a href="{{ route('ulangan.laporan.responses', $ulangan) }}" class="inline-flex items-center px-4 py-2 mt-4 sm:mt-0 bg-gray-600 hover:bg-gray-700 text-white font-semibold text-xs uppercase tracking-widest rounded-md transition">
                &larr; Kembali ke Laporan Hasil
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-800 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6 rounded-md shadow" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Rating Kesulitan Soal Otomatis</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Rating ini dihitung berdasarkan persentase jawaban benar dari semua peserta. Tekan tombol untuk memperbarui rating di Bank Soal.</p>
                        </div>
                        {{-- Form dan Tombol untuk Menyimpan Rating --}}
                        <form action="{{ route('ulangan.laporan.update_kesulitan', $ulangan) }}" method="POST" class="mt-4 sm:mt-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold text-xs uppercase tracking-widest rounded-md transition">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Rating ke Bank Soal
                            </button>
                        </form>
                    </div>

                     <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/12">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-5/12">Pertanyaan</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/12">Statistik (Benar/Menjawab)</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/12">Persentase Benar</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-2/12">Rating Kesulitan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($analisisSoal as $index => $soal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="prose prose-sm max-w-none text-gray-800 dark:text-gray-200">{!! Str::limit(strip_tags($soal->pertanyaan), 100) !!}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-green-600 dark:text-green-400 font-bold">{{ $soal->jumlah_benar }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">/ {{ $soal->jumlah_menjawab }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center font-semibold text-gray-800 dark:text-gray-200">
                                            {{ number_format($soal->persentase_benar, 1) }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @php
                                                $colorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100'; // Sedang
                                                if ($soal->tingkat_kesulitan_otomatis == 'Mudah') $colorClass = 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                                if ($soal->tingkat_kesulitan_otomatis == 'Sulit') $colorClass = 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                                            @endphp
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ $soal->tingkat_kesulitan_otomatis }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                     <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada data jawaban untuk dianalisis.
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
