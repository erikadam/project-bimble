<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Informasi Peserta</h1>

            {{-- Tampilkan informasi paket dan jenjang yang sudah dipilih --}}
            <p class="text-center text-gray-600">Paket: {{ $paketTryout->nama_paket }}</p>
            <p class="text-center text-gray-600 mb-6">Jenjang: <span class="font-semibold">{{ $jenjang }}</span></p>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('siswa.ujian.start', $paketTryout->id) }}">
                @csrf
                <div class="mb-4">
                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required autofocus />
                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                </div>

                {{-- Input jenjang pendidikan sekarang disembunyikan --}}
                <input type="hidden" name="jenjang_pendidikan" value="{{ $jenjang }}">

                <div class="mb-4">
                    <x-input-label for="kelompok" :value="__('Kelompok / Kelas')" />
                    <x-text-input id="kelompok" class="block mt-1 w-full" type="text" name="kelompok" :value="old('kelompok')" required placeholder="Contoh: 6A / Kelompok B" />
                    <x-input-error :messages="$errors->get('kelompok')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('siswa.pilih_paket', ['jenjang' => $jenjang]) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium">
                        &larr; Kembali Pilih Paket
                    </a>
                    <x-primary-button>
                        {{ __('Lanjut') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
