<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-900 p-4">
        <div class="w-full max-w-4xl bg-gray-800 p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-white mb-2">Pilih Paket Ujian</h1>
            <p class="text-center text-gray-300 mb-8">Silakan pilih paket tryout yang ingin Anda kerjakan untuk jenjang <span class="font-semibold">{{ $jenjang }}</span>.</p>

            <div class="space-y-4">
                @forelse ($paketTryouts as $paket)
                    <div x-data="countdownTimer({{ $paket->waktu_mulai_timestamp ?? 'null' }}, {{ $paket->waktu_selesai_timestamp ?? 'null' }}, {{ $paket->server_now_timestamp ?? 'null' }})"
                         class="block p-6 bg-gray-800 border rounded-lg shadow-sm transition-all duration-300"
                         :class="{
                             'border-gray-700 hover:border-yellow-400': !isEvent || status === 'Berlangsung',
                             'border-gray-700 opacity-70': isEvent && (status === 'Akan Datang' || status === 'Selesai'),
                             'cursor-pointer': !isEvent || status === 'Berlangsung',
                             'cursor-not-allowed': isEvent && status === 'Selesai'
                         }">

                        <a href="{{ $paket->tipe_paket == 'event' ? route('siswa.ujian.mulai', $paket->id) : route('siswa.ujian.mulai', $paket->id) }}"
                           class="flex justify-between items-center space-x-4 h-full"
                           :class="{'pointer-events-none': isEvent && status !== 'Sedang Berlangsung'}">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-white">{{ $paket->nama_paket }}</h3>
                                <p class="text-sm text-gray-400">{{ $paket->deskripsi }}</p>
                                <p class="text-xs text-gray-400 mt-2">Dibuat oleh: <span class="font-semibold">{{ $paket->guru->name }}</span></p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                @if ($paket->tipe_paket === 'event')
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full" :class="{'bg-yellow-400 text-yellow-900': status === 'Sedang Berlangsung', 'bg-gray-700 text-gray-400': status !== 'Sedang Berlangsung'}" x-text="status"></span>
                                @else
                                    <span class="inline-block px-3 py-1 text-sm font-semibold bg-green-500 text-white rounded-full">{{ ucfirst($paket->tipe_paket) }}</span>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">Durasi: {{ $paket->durasi_menit }} menit</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="bg-gray-700 p-6 rounded-lg text-center text-gray-400">
                        Belum ada paket ujian yang tersedia untuk jenjang ini.
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
</x-guest-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('countdownTimer', (startTime, endTime, serverNow) => ({
            isEvent: startTime !== null,
            status: 'Loading...',
            countdownText: '',
            serverNow: serverNow,
            interval: null,

            init() {
                if (this.isEvent) {
                    this.updateStatus();
                    this.interval = setInterval(() => this.updateStatus(), 1000);
                }
            },

            updateStatus() {
                const now = Math.floor(Date.now() / 1000);
                // Menyesuaikan waktu klien dengan waktu server
                const timeDiff = now - this.serverNow;
                const currentTime = Date.now() / 1000 - timeDiff;

                const start = startTime;
                const end = endTime;

                if (currentTime < start) {
                    this.status = 'Akan Datang';
                    this.countdownText = `Dimulai dalam: ${this.formatDuration(start - currentTime)}`;
                } else if (currentTime >= start && currentTime < end) {
                    this.status = 'Sedang Berlangsung';
                    this.countdownText = `Berakhir dalam: ${this.formatDuration(end - currentTime)}`;
                } else {
                    this.status = 'Telah Selesai';
                    this.countdownText = 'Ujian telah berakhir.';
                    clearInterval(this.interval);
                }
            },

            formatDuration(seconds) {
                if (seconds < 0) seconds = 0;
                const d = Math.floor(seconds / (3600 * 24));
                const h = Math.floor(seconds % (3600 * 24) / 3600);
                const m = Math.floor(seconds % 3600 / 60);
                const s = Math.floor(seconds % 60);

                let parts = [];
                if (d > 0) parts.push(`${d}h`);
                if (h > 0) parts.push(`${h}j`);
                if (m > 0) parts.push(`${m}m`);
                if (s >= 0) parts.push(`${s}d`);

                return parts.slice(0, 3).join(' ');
            }
        }));
    });
</script>
