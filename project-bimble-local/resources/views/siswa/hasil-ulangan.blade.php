<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 text-white p-4">
        <div class="w-full max-w-2xl bg-gray-800 p-8 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-center mb-4">Hasil Ulangan</h1>
            {{-- Menggunakan relasi untuk mendapatkan nama ulangan --}}
            <p class="text-center text-gray-400 mb-6">Ulangan: {{ $ulanganSession->ulangan->nama_ulangan }}</p>

            <div class="bg-gray-700 border-l-4 border-yellow-500 text-white p-4 mb-6 rounded" role="alert">
                <p class="font-bold">Informasi Peserta</p>
                {{-- Menggunakan data dari $ulanganSession --}}
                <p class="text-sm">Nama: {{ $ulanganSession->nama_siswa }}</p>
                <p class="text-sm">Kelas: {{ $ulanganSession->kelas }}</p>
                <p class="text-sm">Asal Sekolah: {{ $ulanganSession->asal_sekolah }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-center mb-6">
                <div class="bg-green-800 p-4 rounded-lg">
                    {{-- Menggunakan jumlah_benar dari $ulanganSession --}}
                    <p class="text-4xl font-bold text-green-400">{{ $ulanganSession->jumlah_benar }}</p>
                    <p class="text-sm text-green-300">Jawaban Benar</p>
                </div>
                <div class="bg-red-800 p-4 rounded-lg">
                    {{-- Menggunakan jumlah_salah dari $ulanganSession --}}
                    <p class="text-4xl font-bold text-red-400">{{ $ulanganSession->jumlah_salah }}</p>
                    <p class="text-sm text-red-300">Jawaban Salah</p>
                </div>
            </div>

            <div class="text-center">
                {{-- Tombol untuk kembali ke halaman utama --}}
                <a href="{{ route('welcome') }}" class="inline-block px-6 py-3 text-sm font-semibold text-center text-white uppercase transition-all duration-200 bg-yellow-500 rounded-lg hover:bg-yellow-600">
                    Kembali ke Halaman Utama
                </a>

                {{-- INI TOMBOL BARU UNTUK REVIEW --}}
                <a href="{{ route('siswa.ulangan.review', $ulanganSession->id) }}" class="inline-block px-6 py-3 ml-4 text-sm font-semibold text-center text-gray-900 uppercase transition-all duration-200 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Review Jawaban
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
