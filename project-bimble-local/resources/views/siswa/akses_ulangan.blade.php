<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Akses Halaman Ulangan</h1>
            <p class="text-center text-gray-600 mb-4">Silakan pilih jenjang pendidikan untuk melihat daftar ulangan yang tersedia.</p>

            <div class="space-y-4">
                <a href="{{ route('siswa.pilih_ulangan', ['jenjang' => 'SD']) }}" class="block w-full text-center px-4 py-3 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 transition duration-200">
                    Ulangan SD
                </a>
                <a href="{{ route('siswa.pilih_ulangan', ['jenjang' => 'SMP']) }}" class="block w-full text-center px-4 py-3 bg-green-500 text-white rounded-md shadow hover:bg-green-600 transition duration-200">
                    Ulangan SMP
                </a>
                <a href="{{ route('siswa.pilih_ulangan', ['jenjang' => 'SMA']) }}" class="block w-full text-center px-4 py-3 bg-purple-500 text-white rounded-md shadow hover:bg-purple-600 transition duration-200">
                    Ulangan SMA
                </a>
                <hr class="my-4">
                <p class="text-center text-sm text-gray-500">
                    Anda juga dapat memilih dari berbagai pilihan ujian lainnya.
                </p>
                 <div class="space-y-2 mt-4">
                    <a href="{{ route('siswa.pilih_jenjang') }}" class="block w-full text-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md shadow hover:bg-gray-300 transition duration-200">
                        Pilih Ujian Lainnya
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
