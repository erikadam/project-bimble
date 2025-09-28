<x-guest-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">
                    Tryout Pacu <span class="text-yellow-400">{{ $jenjang }}</span>
                </h1>
                <p class="mt-4 text-xl text-gray-400">
                    Pilih tryout yang tersedia untuk memulai ujian.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($paketTryouts as $paket)
                    {{-- DITAMBAH: x-data untuk inisialisasi countdown --}}
                    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden flex flex-col"
                         x-data="countdown({{ $paket->server_now_timestamp ?? 0 }}, {{ $paket->waktu_mulai_timestamp ?? 0 }})">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-bold text-white mb-2">{{ $paket->nama_paket }}</h3>
                                @if ($paket->event_status == 'Sedang Berlangsung')
                                    <span class="text-xs font-semibold bg-green-500 text-white px-2 py-1 rounded-full animate-pulse">LIVE</span>
                                @elseif ($paket->event_status == 'Akan Datang')
                                    <span class="text-xs font-semibold bg-blue-500 text-white px-2 py-1 rounded-full">SEGERA</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-400 mb-4">
                                Diselenggarakan oleh: {{ $paket->guru->name ?? 'Admin' }}
                            </p>
                            <div class="text-sm text-gray-300 space-y-2">
                                <p><i class="fas fa-calendar-alt w-4 mr-2 text-yellow-400"></i> Waktu Mulai: {{ \Carbon\Carbon::parse($paket->waktu_mulai)->format('d M Y, H:i') }} WIB</p>
                                <p><i class="fas fa-stopwatch w-4 mr-2 text-yellow-400"></i> Total Durasi: {{ $paket->durasi_menit }} Menit</p>
                                <p><i class="fas fa-book w-4 mr-2 text-yellow-400"></i> {{ $paket->mataPelajaran->count() }} Mata Pelajaran</p>
                            </div>

                            {{-- =============================================== --}}
                            {{-- DITAMBAH: Blok untuk menampilkan countdown --}}
                            {{-- =============================================== --}}
                            @if ($paket->event_status == 'Akan Datang')
                            <div class="mt-4 text-center border-t border-gray-700 pt-4">
                                <p class="text-sm text-gray-400">Akan dimulai dalam:</p>
                                <p class="text-3xl font-mono font-bold text-yellow-400" x-text="formattedTime"></p>
                            </div>
                            @endif
                        </div>

                        <div class="bg-gray-700 p-4">
                            @if ($paket->event_status == 'Akan Datang')
                                <div class="text-center">
                                    <p class="text-sm text-yellow-300 font-semibold">Belum dimulai</p>
                                    <p class="text-xs text-gray-400">Tombol akan muncul saat waktu dimulai.</p>
                                </div>
                            @elseif ($paket->event_status == 'Sedang Berlangsung')
                                 <a href="{{ route('siswa.ujian.mulai', $paket->id) }}" class="block w-full text-center bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-500 transition duration-300">
                                    Mulai Kerjakan
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center bg-gray-800 rounded-xl shadow-lg p-12">
                        <p class="text-xl text-gray-400">
                            Belum ada Tryout Pacu yang tersedia untuk jenjang {{ $jenjang }}.
                        </p>
                    </div>
                @endforelse
            </div>
             <div class="mt-12 text-center">
                <a href="{{ route('siswa.pilih_jenjang') }}" class="text-gray-300 hover:text-yellow-400 transition">
                    &larr; Kembali ke Pilih Jenis Tryout
                </a>
            </div>
        </div>
    </div>

    {{-- =================================================================== --}}
    {{-- DITAMBAH: Script Alpine.js untuk logika countdown --}}
    {{-- =================================================================== --}}
    <script>
        function countdown(serverNow, startTime) {
            return {
                timeLeft: startTime > serverNow ? startTime - serverNow : 0,
                timer: null,
                init() {
                    // Jika waktu sudah lewat, jangan jalankan timer
                    if (this.timeLeft <= 0) return;

                    this.timer = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            clearInterval(this.timer);
                            // Setelah countdown selesai, refresh halaman agar tombol "Mulai" muncul
                            location.reload();
                        }
                    }, 1000);
                },
                get formattedTime() {
                    if (this.timeLeft <= 0) return '00:00:00';
                    const days = Math.floor(this.timeLeft / (3600*24));
                    const hours = Math.floor((this.timeLeft % (3600*24)) / 3600);
                    const minutes = Math.floor((this.timeLeft % 3600) / 60);
                    const seconds = this.timeLeft % 60;

                    if (days > 0) {
                        return `${days} Hari ${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                    }
                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }
        }
    </script>
</x-guest-layout>
