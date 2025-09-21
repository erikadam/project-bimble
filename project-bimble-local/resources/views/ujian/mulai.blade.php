<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Mulai Demo Ujian</h1>
            <p class="text-center text-gray-600 mb-4">Paket: {{ $paketTryout->nama_paket }}</p>
            <div class="flex justify-center items-center space-x-2 mb-6">
                <span class="px-3 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">{{ $jenjang }}</span>
                <span class="px-3 py-1 text-xs font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full">{{ $paketTryout->tipe_paket }}</span>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('demo.ujian.start', $paketTryout->id) }}">
                @csrf
                <div class="mb-4">
                    <x-input-label for="nama_siswa" :value="__('Nama Anda')" />
                    <x-text-input id="nama_siswa" class="block mt-1 w-full" type="text" name="nama_siswa" required autofocus />
                    <x-input-error :messages="$errors->get('nama_siswa')" class="mt-2" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Mulai Ujian') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
