<x-guest-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center text-white mb-8">Pilih Event Tryout - Jenjang {{ $jenjang ?? 'Semua' }}</h1>

        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($paketTryouts as $paket)
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <h2 class="text-xl font-bold text-white mb-2">{{ $paket->nama_paket }}</h2>
                        <p class="text-gray-400 text-sm mb-4">Oleh: {{ $paket->guru->name }}</p>
                        <div class="prose prose-sm prose-invert text-gray-300 max-w-none">
                            {!! $paket->deskripsi !!}
                        </div>
                    </div>
                    <div class="p-6 bg-gray-700">
                        <div class="text-center mb-4">
                            @if ($paket->event_status == 'Akan Datang')
                                <p class="text-sm text-gray-300">Akan dimulai dalam:</p>
                                {{-- Atribut 'data-countdown' untuk dibaca oleh JavaScript --}}
                                <div class="text-2xl font-bold text-yellow-400"
                                     data-countdown="{{ \Carbon\Carbon::parse($paket->waktu_mulai)->format('Y/m/d H:i:s') }}">
                                     </div>
                            @elseif ($paket->event_status == 'Sedang Berlangsung')
                                <p class="text-2xl font-bold text-green-400">Sedang Berlangsung!</p>
                            @else
                                <p class="text-2xl font-bold text-red-400">Telah Selesai</p>
                            @endif
                        </div>

                        @if ($paket->event_status == 'Akan Datang')
                            <button disabled class="w-full bg-gray-500 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                Belum Dimulai
                            </button>
                        @elseif ($paket->event_status == 'Sedang Berlangsung')
                            <a href="{{ route('siswa.ujian.mulai', $paket->id) }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Mulai Kerjakan
                            </a>
                        @else
                            <button disabled class="w-full bg-red-500 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                Event Selesai
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3 text-center py-16">
                    <p class="text-gray-400 text-lg">Tidak ada event tryout yang tersedia untuk jenjang ini.</p>
                </div>
            @endforelse
                        </div>
             <div class="mt-8 text-center">
                <a href="{{ route('siswa.pilih_jenjang') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-gray-700 rounded-md font-semibold text-xs text-yellow-400 uppercase tracking-widest shadow-sm hover:bg-gray-700">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        // Pastikan kode berjalan setelah halaman dan semua skrip siap
        $(function() {
            // Cari semua elemen yang punya atribut 'data-countdown'
            $('[data-countdown]').each(function() {
                var $this = $(this), finalDate = $(this).data('countdown');

                // Jalankan plugin countdown pada elemen ini
                $this.countdown(finalDate, function(event) {
                    // Atur format tampilan: Hari Jam:Menit:Detik
                    $this.html(event.strftime('%D hari %H:%M:%S'));
                }).on('finish.countdown', function() {
                    // Saat countdown selesai, refresh halaman agar status tombol berubah
                    location.reload();
                });
            });
        });
    </script>
    @endpush
</x-guest-layout>
