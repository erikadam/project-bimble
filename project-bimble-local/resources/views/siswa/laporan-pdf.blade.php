<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Hasil Ujian</h1>
            <p class="text-center text-gray-600 mb-6">Selamat, Anda telah menyelesaikan ujian!</p>

            <div class="mb-8 p-4 bg-gray-100 rounded-lg">
                <p class="text-lg font-medium">Nama: {{ $namaLengkap }}</p>
                <p class="text-lg font-medium">Jenjang: {{ $jenjangPendidikan }}</p>
                <p class="text-lg font-medium">Kelompok: {{ $kelompok }}</p>
                <p class="text-lg font-medium">Paket: {{ $paketTryout->nama_paket }}</p>
                <p class="text-lg font-medium">Kode Soal: <span class="font-mono text-gray-800">{{ $paketTryout->kode_soal }}</span></p>
                <p class="text-lg font-medium">Total Waktu Pengerjaan: {{ floor($totalWaktuPengerjaan / 60) }} menit {{ $totalWaktuPengerjaan % 60 }} detik</p>
            </div>

            <div class="flex flex-col items-center space-y-4 mb-8">
                <div class="text-6xl font-bold text-indigo-600">{{ number_format($skorKeseluruhan, 2) }}</div>
                <p class="text-xl font-medium">Skor Keseluruhan Anda</p>
            </div>

            <hr class="my-6">

            <h3 class="text-xl font-bold mb-4">Hasil per Mata Pelajaran</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Soal</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-green-600 uppercase tracking-wider">Jawaban Benar</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-red-600 uppercase tracking-wider">Jawaban Salah</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasilPerMapel as $namaMapel => $hasil)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 border-b border-gray-200 text-sm font-medium">{{ $namaMapel }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $hasil['total_soal'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm text-green-600">{{ $hasil['total_benar'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm text-red-600">{{ $hasil['total_soal'] - $hasil['total_benar'] }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm font-bold">{{ number_format($hasil['nilai'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('siswa.ujian.unduh_hasil', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Unduh Hasil (PDF)
                </a>
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    &larr; Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
