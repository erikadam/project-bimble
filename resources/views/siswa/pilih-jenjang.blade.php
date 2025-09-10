<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Pilih Jenis dan Jenjang Ujian</h1>
            <p class="text-center text-gray-600 mb-8">Silakan pilih jenis ujian yang ingin Anda kerjakan.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- KOTAK UNTUK TRYOUT BIASA --}}
                <div class="border p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4 text-center">Tryout Fleksibel</h2>
                    <div class="space-y-4">
                        <a href="{{ route('siswa.pilih_paket', ['jenjang' => 'SD']) }}" class="block p-4 bg-white border-2 border-transparent rounded-lg shadow text-center transform hover:-translate-y-1 hover:border-blue-500 transition-all duration-300">
                            <h3 class="text-xl font-bold">SD</h3>
                        </a>
                        <a href="{{ route('siswa.pilih_paket', ['jenjang' => 'SMP']) }}" class="block p-4 bg-white border-2 border-transparent rounded-lg shadow text-center transform hover:-translate-y-1 hover:border-green-500 transition-all duration-300">
                           <h3 class="text-xl font-bold">SMP</h3>
                        </a>
                        <a href="{{ route('siswa.pilih_paket', ['jenjang' => 'SMA']) }}" class="block p-4 bg-white border-2 border-transparent rounded-lg shadow text-center transform hover:-translate-y-1 hover:border-red-500 transition-all duration-300">
                           <h3 class="text-xl font-bold">SMA</h3>
                        </a>
                    </div>
                </div>

                {{-- KOTAK UNTUK TRYOUT EVENT --}}
                <div class="border p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4 text-center">Tryout Event (Terjadwal)</h2>
                    <div class="space-y-4">
                         <a href="{{ route('siswa.pilih_event', ['jenjang' => 'SD']) }}" class="block p-4 bg-white border-2 border-transparent rounded-lg shadow text-center transform hover:-translate-y-1 hover:border-blue-500 transition-all duration-300">
                            <h3 class="text-xl font-bold">SD</h3>
                        </a>
                        <a href="{{ route('siswa.pilih_event', ['jenjang' => 'SMP']) }}" class="block p-4 bg-white border-2 border-transparent rounded-lg shadow text-center transform hover:-translate-y-1 hover:border-green-500 transition-all duration-300">
                           <h3 class="text-xl font-bold">SMP</h3>
                        </a>
                        <a href="{{ route('siswa.pilih_event', ['jenjang' => 'SMA']) }}" class="block p-4 bg-white border-2 border-transparent rounded-lg shadow text-center transform hover:-translate-y-1 hover:border-red-500 transition-all duration-300">
                           <h3 class="text-xl font-bold">SMA</h3>
                        </a>
                    </div>
                </div>
            </div>
             <div class="mt-8 text-center">
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
