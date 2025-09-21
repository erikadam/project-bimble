<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Detail Paket') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showModal: false, modalSoal: [], modalTitle: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    {{-- HEADER & STATUS EVENT --}}
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-yellow-400">{{ $paketTryout->nama_paket }}</h3>
                            <div class="flex space-x-2 mt-2">
                                <span class="px-3 py-1 text-sm font-semibold leading-tight text-blue-200 bg-blue-800 rounded-full">{{ $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? 'N/A' }}</span>
                                <span class="px-3 py-1 text-sm font-semibold leading-tight text-purple-200 bg-purple-800 rounded-full">{{ Str::ucfirst($paketTryout->tipe_paket) }}</span>
                                <span class="font-mono bg-gray-700 px-3 py-1 text-sm rounded-full text-gray-300">{{ $paketTryout->kode_soal }}</span>
                            </div>
                        </div>
                        @if ($paketTryout->tipe_paket == 'event')
                            <div x-data="countdownTimer({{ $paketTryout->waktu_mulai_timestamp ?? 'null' }}, {{ $paketTryout->waktu_selesai_timestamp ?? 'null' }}, {{ $paketTryout->server_now_timestamp ?? 'null' }})" x-init="init()" class="text-right">
                                <p class="font-semibold text-gray-300">{{ \Carbon\Carbon::parse($paketTryout->waktu_mulai)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                                <p class="px-2 py-1 text-xs font-semibold leading-tight rounded-full inline-block mt-1" :class="{'text-green-200 bg-green-800': status === 'Sedang Berlangsung', 'text-blue-200 bg-blue-800': status === 'Akan Datang', 'text-gray-200 bg-gray-700': status === 'Telah Selesai'}" x-text="status"></p>
                                <p class="text-sm font-mono mt-1 text-gray-400" x-text="countdownText"></p>
                            </div>
                        @endif
                    </div>
                    @if ($paketTryout->deskripsi)
                        <p class="mt-4 text-gray-300">{{ $paketTryout->deskripsi }}</p>
                    @endif

                    <hr class="my-6 border-gray-700">

                    <h3 class="text-xl font-bold text-gray-200 mb-4">Informasi Paket</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-700 rounded-lg">
                            <p class="text-gray-400">Total Waktu Pengerjaan:</p>
                            <span class="font-semibold text-lg text-yellow-400">{{ $totalDurasi }} menit</span>
                        </div>
                        <div class="p-4 bg-gray-700 rounded-lg">
                            <p class="text-gray-400">Jumlah Mata Pelajaran:</p>
                            <span class="font-semibold text-lg text-yellow-400">{{ $paketTryout->mataPelajaran->count() }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-xl font-bold text-gray-200 mb-4">Daftar Mata Pelajaran & Soal</h3>
                        <div class="space-y-2">
                            @foreach ($paketTryout->mataPelajaran as $mapel)
                                @php
                                    $soalTerkait = $soalPerMapel[$mapel->id] ?? collect();
                                @endphp
                                <div @click="showModal = true; modalTitle = '{{ addslashes($mapel->nama_mapel) }}'; modalSoal = {{ json_encode($soalTerkait) }}" class="p-3 bg-gray-700 rounded-md flex justify-between items-center hover:bg-gray-600 cursor-pointer transition">
                                    <div>
                                        <span class="font-semibold text-gray-100">{{ $mapel->nama_mapel }}</span>
                                        <span class="text-sm text-gray-400 ml-2">({{ $soalTerkait->count() }} soal)</span>
                                    </div>
                                    <span class="text-sm text-gray-300">{{ $mapel->pivot->durasi_menit }} menit</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-6 space-x-2">
                        <a href="{{ route('paket-tryout.index', ['jenjang' => $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? null]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                            &larr; Kembali
                        </a>
                        <a href="{{ route('paket-tryout.edit', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400">
                            Edit Paket
                        </a>
                        <form action="{{ route('paket-tryout.toggle_status', $paketTryout->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @if ($paketTryout->status == 'published')
                        {{-- Tombol untuk Unpublish (Arsipkan) --}}
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400">
                            Arsipkan
                        </button>
                    @else
                        {{-- Tombol untuk Publish --}}
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                            Publikasikan
                        </button>
                    @endif
                </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showModal" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
            <div @click.outside="showModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-gray-100">Daftar Soal untuk <span x-text="modalTitle"></span></h3>
                </div>
                <div class="p-6 overflow-y-auto">
                    <ul class="list-decimal list-inside space-y-2 text-gray-300">
                        <template x-for="soal in modalSoal" :key="soal.id">
                            <li x-html="soal.pertanyaan"></li>
                        </template>
                        <template x-if="modalSoal.length === 0">
                            <p class="text-gray-500">Tidak ada soal yang dipilih untuk mata pelajaran ini.</p>
                        </template>
                    </ul>
                </div>
                <div class="p-4 bg-gray-900 border-t border-gray-700 rounded-b-lg text-right">
                    <button @click="showModal = false" class="px-4 py-2 bg-gray-600 text-gray-200 rounded-md hover:bg-gray-500">Tutup</button>
                </div>
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
</x-app-layout>
