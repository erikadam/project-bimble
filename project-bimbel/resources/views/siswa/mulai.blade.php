<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 p-4">
        <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-white mb-2">Informasi Peserta</h1>

            {{-- Tampilkan informasi paket dan jenjang yang sudah dipilih --}}
            <p class="text-center text-gray-300">Paket: {{ $paketTryout->nama_paket }}</p>
            <p class="text-center text-gray-300 mb-6">Jenjang: <span class="font-semibold">{{ $jenjang }}</span></p>

            @if(session('error'))
                <div class="bg-red-900 border border-red-600 text-red-300 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('siswa.ujian.start', $paketTryout->id) }}">
                @csrf
                <div class="mb-4">
                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" class="text-gray-300" />
                    <x-text-input id="nama_lengkap" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required autofocus />
                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                </div>

                {{-- Input jenjang pendidikan sekarang disembunyikan --}}
                <input type="hidden" name="jenjang_pendidikan" value="{{ $jenjang }}">

                <div class="mb-4">
                    <x-input-label for="kelompok" :value="__('Kelompok / Kelas')" class="text-gray-300" />
                    <x-text-input id="kelompok" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400" type="text" name="kelompok" :value="old('kelompok')" required placeholder="Contoh: 6A / Kelompok B" />
                    <x-input-error :messages="$errors->get('kelompok')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('siswa.pilih_paket', ['jenjang' => $jenjang]) }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white font-medium">
                        &larr; Kembali Pilih Paket
                    </a>
                    <x-primary-button class="bg-yellow-400 text-yellow-900 hover:bg-yellow-500">
                        {{ __('Lanjut') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
