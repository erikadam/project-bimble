<x-guest-layout>
    {{-- Inisialisasi Alpine.js untuk state modal --}}
    <div x-data="{ openModal: false }" class="flex flex-col items-center justify-center min-h-screen bg-gray-900 p-4">
        <div class="w-full max-w-md bg-gray-800 p-8 rounded-lg shadow-md text-center">
            <h1 class="text-2xl font-bold text-white mb-4">Sesi Berikutnya</h1>
            <p class="text-gray-400 mb-6">
                @if(isset($mapelSebelumnya) && $mapelSebelumnya !== 'Sesi Sebelumnya')
                    Anda telah menyelesaikan mata pelajaran <span class="font-semibold text-white">{{ $mapelSebelumnya }}</span>.
                @else
                    Harap tunggu hingga sesi ujian dimulai.
                @endif
            </p>

            <div class="p-4 bg-gray-700 border border-gray-600 rounded-lg">
                <p class="text-gray-300">Mata pelajaran selanjutnya, <span class="font-semibold text-white">{{ $mapelSelanjutnya }}</span>, akan dimulai dalam:</p>

                {{-- KODE COUNTDOWN ASLI ANDA DIGUNAKAN DI SINI --}}
                <div x-data="countdownTimer({{ $waktuMulaiSelanjutnya }}, {{ now()->timestamp }})" x-init="init()" class="text-4xl font-bold text-yellow-400 mt-2">
                    <span x-text="countdownText"></span>
                </div>
            </div>

            {{-- TOMBOL BARU UNTUK MELIHAT JADWAL --}}
            <div class="mt-6">
                <button @click="openModal = true" class="text-sm font-semibold text-yellow-400 hover:text-yellow-300 transition-colors">
                    Lihat Jadwal Lengkap
                </button>
            </div>

            <p class="text-xs text-gray-500 mt-6">Halaman ini akan otomatis dimuat ulang saat waktunya tiba. Jangan tutup halaman ini.</p>
        </div>

        <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4" style="display: none;">
            <div @click.outside="openModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-auto flex flex-col" x-show="openModal" x-transition>
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white">Jadwal Ujian: {{ $paketTryout->nama_paket }}</h3>
                </div>

                <div class="p-6 overflow-y-auto" style="max-height: 60vh;">
                    <ul class="space-y-2">
                        @forelse ($fullSchedule as $jadwal)
                            <li class="p-3 bg-gray-700 rounded-md flex justify-between items-center border border-gray-600">
                                <span class="font-medium text-gray-200">{{ $jadwal['nama_mapel'] }}</span>
                                <span class="text-sm text-yellow-400 font-mono bg-gray-900/50 px-2 py-1 rounded">
                                    {{ $jadwal['waktu_mulai']->format('H:i') }} - {{ $jadwal['waktu_selesai']->format('H:i') }}
                                </span>
                            </li>
                        @empty
                            <li class="p-3 text-center text-gray-400">
                                Jadwal tidak tersedia.
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div class="px-6 py-4 bg-gray-900/50 text-right rounded-b-lg">
                    <button @click="openModal = false" class="px-4 py-2 bg-gray-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-gray-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT COUNTDOWN ASLI ANDA --}}
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
                        this.countdownText = 'Memuat...';
                        clearInterval(this.interval);
                        // Tambahkan baris ini untuk refresh segera jika waktu sudah lewat
                        window.location.reload();
                    } else {
                        this.countdownText = this.formatDuration(remaining);
                    }
                },
                formatDuration(ms) {
                    if (ms < 0) ms = 0;
                    const seconds = Math.floor((ms / 1000) % 60).toString().padStart(2, '0');
                    const minutes = Math.floor((ms / (1000 * 60)) % 60).toString().padStart(2, '0');
                    const hours = Math.floor((ms / (1000 * 60 * 60)) % 24).toString().padStart(2, '0');

                    return `${hours}:${minutes}:${seconds}`;
                }
            }
        }
    </script>
</x-guest-layout>
