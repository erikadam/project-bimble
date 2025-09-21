{{-- resources/views/siswa/mulai-ulangan.blade.php --}}
<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 p-4">
        <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-md">
            {{-- Menggunakan variabel $ulangan dari model Ulangan --}}
            <h1 class="text-2xl font-bold text-center text-white mb-2">Mulai Ulangan</h1>
            <p class="text-center text-gray-300 mb-6">Ulangan: {{ $ulangan->nama_ulangan }}</p>

            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form ini akan membuat sesi ulangan baru --}}
            <form method="POST" action="{{ route('siswa.ulangan.start', $ulangan->id) }}">
                @csrf
                <div class="text-center">
                    <p class="text-gray-300 mb-2">Anda akan memulai ulangan sebagai:</p>
                    <p class="font-semibold text-white text-lg">{{ Session::get('calon_peserta_ulangan.nama_lengkap') }}</p>
                    <p class="text-gray-400">{{ Session::get('calon_peserta_ulangan.kelas') }} - {{ Session::get('calon_peserta_ulangan.asal_sekolah') }}</p>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Mulai Kerjakan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
