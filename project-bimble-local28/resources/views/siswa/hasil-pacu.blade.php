<x-guest-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 text-gray-200 border-b border-gray-700">
                    <h2 class="text-2xl font-extrabold text-center text-white mb-2">Hasil Ujian Tryout</h2>
                    <p class="text-center text-yellow-400 font-semibold">{{ $paketTryout->nama_paket }}</p>
                </div>

                {{-- DATA SISWA & TRYOUT --}}
                <div class="p-8 space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4 bg-gray-900/50 p-4 rounded-lg">
                        <div>
                            <p class="text-gray-400">Nama Peserta</p>
                            <p class="font-bold text-white text-base">{{ $student->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Asal Sekolah</p>
                            <p class="font-bold text-white text-base">{{ $student->asal_sekolah }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Jenjang</p>
                            <p class="font-bold text-white text-base">{{ $student->jenjang_pendidikan }} - Kelas {{ $student->kelas }}</p>
                        </div>
                         <div>
                            <p class="text-gray-400">Kelompok</p>
                            <p class="font-bold text-white text-base">{{ $student->kelompok }}</p>
                        </div>
                    </div>
                </div>

                <div class="px-8 pb-8">
                    <h3 class="text-lg font-bold text-white mb-4">Rincian Hasil per Mata Pelajaran</h3>
                    <div class="space-y-3 border-t border-gray-700 pt-4">
                        @forelse ($semuaMapelPaket as $mapel)
                            @php
                                $hasil = $hasilPerMapel[$mapel->id] ?? null;
                                $totalSalah = $hasil && $hasil['dikerjakan'] ? $hasil['total_soal'] - $hasil['total_benar'] : 0;
                            @endphp

                            <div class="bg-gray-700 p-4 rounded-lg">
                                <p class="font-semibold text-white text-base mb-2">{{ $mapel->nama_mapel }}</p>
                                @if($hasil && $hasil['dikerjakan'])
                                    <div class="flex space-x-6 text-sm">
                                        <p class="text-green-400">
                                            <span class="font-bold">{{ $hasil['total_benar'] }}</span> Benar
                                        </p>
                                        <p class="text-red-400">
                                            <span class="font-bold">{{ $totalSalah }}</span> Salah
                                        </p>
                                        <p class="text-gray-400">
                                            <span class="font-bold">{{ $hasil['total_soal'] }}</span> Total Soal
                                        </p>
                                    </div>
                                @else
                                     <p class="text-xs text-gray-500 italic">Tidak Dikerjakan</p>
                                @endif
                            </div>
                        @empty
                             <div class="bg-gray-700 p-4 rounded-lg text-center">
                                <p class="text-gray-400">Tidak ada mata pelajaran yang dikerjakan.</p>
                            </div>
                        @endforelse
                    </div>

                     <div class="mt-8 text-center">
                        <a href="{{ route('welcome') }}" class="inline-block px-6 py-3 bg-yellow-500 text-yellow-900 text-sm font-bold uppercase rounded-md hover:bg-yellow-400 transition-transform transform hover:scale-105">
                           Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
