{{-- resources/views/siswa/pilih-ulangan.blade.php --}}
<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 p-4">
        <div class="w-full max-w-4xl">
            <div class="bg-gray-800 p-8 rounded-lg shadow-md mb-6">
                <h1 class="text-2xl font-bold text-center text-white mb-2">Pilih Ulangan</h1>
                @if(Session::has('calon_peserta_ulangan'))
                    <p class="text-center text-gray-300">
                        Selamat datang, <span class="font-semibold">{{ Session::get('calon_peserta_ulangan.nama_lengkap') }}</span>.
                        Silakan pilih ulangan yang ingin Anda kerjakan untuk jenjang <span class="font-semibold">{{ $jenjang }}</span>.
                    </p>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($ulangans as $ulangan)
                    <div class="block p-6 bg-gray-800 border border-gray-700 rounded-lg shadow-sm transition-all duration-300 hover:border-yellow-400">
                        <div class="flex flex-col md:flex-row justify-between">
                            <div class="mb-4 md:mb-0">
                                <h5 class="text-xl font-bold text-white">{{ $ulangan->nama_ulangan }}</h5>
                                <p class="text-gray-400">
                                    @if($ulangan->mataPelajaran)
                                        {{ $ulangan->mataPelajaran->nama_mapel }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                {{-- Tombol ini akan kita fungsikan sekarang --}}
                                <a href="{{ route('siswa.ulangan.mulai', $ulangan->id) }}" class="inline-block px-6 py-3 text-sm font-semibold text-center text-white uppercase transition-all duration-200 bg-yellow-500 rounded-lg hover:bg-yellow-600">
                                    Pilih Ulangan
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="block p-6 text-center bg-gray-800 border border-gray-700 rounded-lg shadow-sm">
                        <h5 class="text-xl font-bold text-white">Belum Ada Ulangan Tersedia</h5>
                        <p class="text-gray-400 mt-2">Saat ini belum ada ulangan yang dipublish untuk jenjang {{ $jenjang }}.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-guest-layout>
