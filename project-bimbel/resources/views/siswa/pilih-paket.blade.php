<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-4xl bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Pilih Paket Ujian</h1>
            <p class="text-center text-gray-600 mb-8">Silakan pilih paket tryout yang ingin Anda kerjakan untuk jenjang <span class="font-semibold">{{ $jenjang }}</span>.</p>

            <div class="space-y-4">
                @forelse ($paketTryouts as $paket)
                    <div x-data="countdownTimer({{ $paket->waktu_mulai_timestamp ?? 'null' }}, {{ $paket->waktu_selesai_timestamp ?? 'null' }}, {{ $paket->server_now_timestamp ?? 'null' }})"
                         class="block p-6 bg-white border rounded-lg shadow-sm transition-all duration-300"
                         :class="{
                             'border-gray-200 hover:border-blue-500': !isEvent || status === 'Berlangsung',
                             'border-gray-200 opacity-70': isEvent && (status === 'Akan Datang' || status === 'Selesai'),
                             'cursor-pointer': !isEvent || status === 'Berlangsung',
                             'cursor-not-allowed': isEvent && status === 'Selesai'
                         }">

                        <a href="{{ $paket->tipe_paket == 'event' && $paket->event_status == 'Telah Selesai' ? '#' : route('siswa.ujian.mulai', $paket->id) }}"
                           class="group"
                           :class="{'pointer-events-none': isEvent && status === 'Selesai'}">

                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-800 group-hover:text-blue-600">
                                        {{ $paket->nama_paket }}
                                    </h4>
                                    {{-- INFORMASI KODE & GURU --}}
                                    <p class="text-gray-500 text-sm mt-1">
                                        Kode: <span class="font-mono text-gray-800 font-semibold">{{ $paket->kode_soal }}</span>
                                        @if($paket->guru)
                                        | Dibuat oleh: <span class="font-medium">{{ $paket->guru->name }}</span>
                                        @endif
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full flex-shrink-0"
                                      :class="{
                                          'text-green-700 bg-green-100': status === 'Berlangsung' || !isEvent,
                                          'text-blue-700 bg-blue-100': status === 'Akan Datang',
                                          'text-gray-700 bg-gray-100': status === 'Selesai'
                                      }">
                                    <span x-text="isEvent ? status : 'Tersedia'"></span>
                                </span>
                            </div>

                            <p x-show="countdownText" class="text-sm font-semibold mt-2"
                               :class="{
                                   'text-blue-600': status === 'Akan Datang',
                                   'text-red-600': status === 'Berlangsung'
                               }" x-text="countdownText">
                            </p>

                            @if ($paket->deskripsi)
                                <p class="text-sm text-gray-600 mt-4 line-clamp-2">{{ $paket->deskripsi }}</p>
                            @endif
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500 border rounded-lg">
                        Tidak ada paket ujian yang tersedia untuk jenjang ini.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('siswa.pilih_jenjang') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                    &larr; Kembali Pilih Jenjang
                </a>
            </div>
        </div>
    </div>

    <script>
        function countdownTimer(startTime, endTime, serverNow) {
            return {
                isEvent: startTime !== null,
                status: '',
                countdownText: '',
                interval: null,

                init() {
                    if (!this.isEvent) return;

                    let now = serverNow * 1000;

                    this.updateStatus(now);

                    this.interval = setInterval(() => {
                        now += 1000;
                        this.updateStatus(now);
                    }, 1000);
                },

                updateStatus(now) {
                    const start = startTime * 1000;
                    const end = endTime * 1000;

                    if (now < start) {
                        this.status = 'Akan Datang';
                        this.countdownText = `Dimulai dalam: ${this.formatDuration(start - now)}`;
                    } else if (now >= start && now < end) {
                        this.status = 'Sedang Berlangsung';
                        this.countdownText = `Berakhir dalam: ${this.formatDuration(end - now)}`;
                    } else {
                        this.status = 'Telah Selesai';
                        this.countdownText = 'Ujian telah berakhir.';
                        clearInterval(this.interval);
                    }
                },

                formatDuration(ms) {
                    if (ms < 0) ms = 0;
                    const seconds = Math.floor((ms / 1000) % 60);
                    const minutes = Math.floor((ms / (1000 * 60)) % 60);
                    const hours = Math.floor((ms / (1000 * 60 * 60)) % 24);
                    const days = Math.floor(ms / (1000 * 60 * 60 * 24));

                    let parts = [];
                    if (days > 0) parts.push(`${days}h`);
                    if (hours > 0) parts.push(`${hours}j`);
                    if (minutes > 0) parts.push(`${minutes}m`);
                    if (seconds >= 0 && days === 0) parts.push(`${seconds}d`);

                    return parts.slice(0, 3).join(' ');
                }
            }
        }
    </script>
</x-guest-layout>
