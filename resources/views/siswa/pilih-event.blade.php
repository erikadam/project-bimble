<x-guest-layout>
    <div class="bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-12">
            <div class="text-center mb-10 relative">
                <h1 class="text-4xl font-extrabold text-white">Event Tryout Terjadwal <span class="text-yellow-400">{{ $jenjang }}</span></h1>
                <p class="mt-2 text-lg text-gray-400">Pilih event yang ingin Anda ikut.</p>
            </div>

            @if(session('error'))
                <div class="bg-red-900 border border-red-700 text-red-300 px-4 py-3 rounded relative mb-6 max-w-4xl mx-auto" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($paketTryouts as $paket)
                    {{-- Alpine.js diinisialisasi pada container yang membungkus card DAN modal --}}
                    <div x-data="{ openModal: false }">
                        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col h-full transition-transform transform hover:-translate-y-2">
                            <div class="p-6 flex-grow">
                                <h2 class="text-xl font-bold text-white">{{ $paket->nama_paket }}</h2>
                                <p class="text-sm text-gray-500 mt-1">Oleh: {{ $paket->guru->name }}</p>

                                {{-- DESKRIPSI DITAMPILKAN DI SINI --}}
                                @if($paket->deskripsi)
                                <p class="text-gray-400 mt-3 text-sm leading-relaxed">
                                    {{ Str::limit($paket->deskripsi, 100) }}
                                </p>
                                @endif

                                {{-- Jadwal real-time di card --}}
                                <div class="mt-4 p-4 bg-gray-700/50 rounded-lg border border-gray-600">
                                    @if ($paket->event_status == 'Sedang Berlangsung' && $paket->current_mapel)
                                        <div class="text-sm">

                                            <p class="font-semibold text-green-400">▶ Sedang Berlangsung</p>
                                            <p class="font-bold text-white text-base mt-1">{{ $paket->current_mapel['nama_mapel'] }}</p>
                                            <p class="text-xs text-gray-400">Berakhir pkl. {{ $paket->current_mapel['waktu_selesai']->format('H:i') }} WIB</p>
                                        </div>
                                    @elseif ($paket->event_status == 'Akan Datang' && $paket->next_mapel)
                                        <div class="text-sm">
                                            <p class="font-semibold text-blue-400">Segera Dimulai</p>
                                            <div x-data="countdownTimer({{ \Carbon\Carbon::parse($paket->waktu_mulai)->timestamp }}, {{ now()->timestamp }})" x-init="init()" class="text-3xl font-bold text-yellow-400 my-2">
                                                <span x-text="countdownText"></span>
                                            </div>
                                            <p class="font-bold text-white text-base mt-1">Mulai pkl. {{ Carbon\Carbon::parse($paket->waktu_mulai)->format('H:i') }} WIB</p>
                                            <p class="text-xs text-gray-400">Sesi pertama: {{ $paket->next_mapel['nama_mapel'] }}</p>
                                        </div>
                                    @else
                                        <p class="text-sm font-semibold text-red-400">Telah Selesai</p>
                                    @endif
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                                <div class="flex justify-between items-center">
                                    <button @click="openModal = true" class="text-sm font-semibold text-yellow-400 hover:text-yellow-300 transition-colors">
                                        Lihat Jadwal
                                    </button>

                                    @if ($paket->event_status == 'Sedang Berlangsung' || $paket->event_status == 'Akan Datang')
                                        <a href="{{ route('siswa.ujian.mulai', $paket->id) }}" class="px-4 py-2 bg-yellow-500 text-yellow-900 text-xs font-bold uppercase rounded-md hover:bg-yellow-400 transition-transform transform hover:scale-105">
                                            Masuk
                                        </a>
                                    @else
                                        <span class="px-4 py-2 bg-gray-600 text-gray-400 text-xs font-bold uppercase rounded-md cursor-not-allowed">
                                            Selesai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4" style="display: none;">
                            <div @click.outside="openModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-auto flex flex-col" x-show="openModal" x-transition>
                                <div class="px-6 py-4 border-b border-gray-700">
                                    <h3 class="text-lg font-bold text-white">Jadwal Ujian: {{ $paket->nama_paket }}</h3>
                                </div>

                                {{-- KONTEN MODAL YANG BISA DI-SCROLL --}}
                                <div class="p-6 overflow-y-auto" style="max-height: 60vh;">
                                    <ul class="space-y-2">
                                        @forelse ($paket->full_schedule as $jadwal)
                                            <li class="p-3 bg-gray-700 rounded-md flex justify-between items-center border border-gray-600">
                                                <span class="font-medium text-gray-200">{{ $jadwal['nama_mapel'] }}</span>
                                                <span class="text-sm text-yellow-400 font-mono bg-gray-900/50 px-2 py-1 rounded">
                                                    {{ $jadwal['waktu_mulai']->format('H:i') }} - {{ $jadwal['waktu_selesai']->format('H:i') }}
                                                </span>
                                            </li>
                                        @empty
                                            <li class="p-3 text-center text-gray-400">
                                                Jadwal belum diatur untuk event ini.
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <script>
    function countdownTimer(startTime, serverNow) {
        return {
            countdownText: '',
            interval: null,
            init() {
                let now = serverNow * 1000;
                this.updateCountdown(now); // Panggil sekali agar tidak ada kedipan
                this.interval = setInterval(() => {
                    now += 1000;
                    this.updateCountdown(now);
                }, 1000);
            },
            updateCountdown(now) {
                const start = startTime * 1000;
                const remaining = start - now;
                if (remaining <= 0) {
                    this.countdownText = 'Segera Dimulai...';
                    clearInterval(this.interval);
                    // Refresh halaman setelah 2 detik untuk update status
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    this.countdownText = this.formatDuration(remaining);
                }
            },
            formatDuration(ms) {
                if (ms < 0) ms = 0;
                const totalSeconds = Math.floor(ms / 1000);
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                let parts = [];
                if (days > 0) parts.push(`${days}h`);
                parts.push(String(hours).padStart(2, '0'));
                parts.push(String(minutes).padStart(2, '0'));
                parts.push(String(seconds).padStart(2, '0'));

                return parts.join(':');
            }
        }
    }
</script>
                                <div class="px-6 py-4 bg-gray-900/50 text-right rounded-b-lg">
                                    <button @click="openModal = false" class="px-4 py-2 bg-gray-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-gray-500">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400 text-lg">Tidak ada event yang tersedia untuk jenjang ini.</p>
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
</x-guest-layout>
