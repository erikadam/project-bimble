<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Hasil Ujian Selesai</h1>
            <p class="text-center text-gray-600 mb-6">Selamat, Anda telah menyelesaikan ujian!</p>

            <div class="mb-8 p-4 bg-gray-100 rounded-lg">
                <p>Nama: <span class="font-medium">{{ $namaLengkap }}</span></p>
                <p>Jenjang: <span class="font-medium">{{ $jenjangPendidikan }}</span></p>
                <p>Kelompok: <span class="font-medium">{{ $kelompok }}</span></p>
                <p>Paket Ujian: <span class="font-medium">{{ $paketTryout->nama_paket }}</span></p>
                <p>Waktu Pengerjaan: <span class="font-medium">{{ floor($totalWaktuPengerjaan / 60) }} menit {{ $totalWaktuPengerjaan % 60 }} detik</span></p>
            </div>

            <h3 class="text-xl font-bold mb-4">Ringkasan Jawaban per Mata Pelajaran</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Soal</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-green-600 uppercase tracking-wider">Jawaban Benar</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-red-600 uppercase tracking-wider">Jawaban Salah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasilPerMapel as $namaMapel => $hasil)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 border-b border-gray-200 text-sm font-medium">{{ $namaMapel }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm text-center">{{ $hasil['total_soal'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm text-center font-semibold text-green-600">{{ $hasil['total_benar'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm text-center font-semibold text-red-600">{{ $hasil['total_soal'] - $hasil['total_benar'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-center space-x-4">
                {{-- Tombol Unduh PDF bisa diaktifkan kembali jika diperlukan --}}
                {{-- <a href="{{ route('siswa.ujian.unduh_hasil', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Unduh Hasil (PDF)
                </a> --}}
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                    &larr; Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
