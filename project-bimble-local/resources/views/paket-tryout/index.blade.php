<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Manajemen Paket Tryout') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-800 border border-green-700 text-green-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-800 border border-red-700 text-red-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                {{-- Filter & Tombol Tambah --}}
                <a href="{{ route('paket-tryout.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">
                    + Buat Paket Baru
                </a>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase">Nama Paket</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase">Tipe</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase">Status</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase">Jadwal Event</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paketTryouts as $paket)
                                <tr class="hover:bg-gray-700/50">
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        {{-- Tautan dikembalikan ke halaman show (detail) --}}
                                        <a href="{{ route('paket-tryout.show', $paket->id) }}" class="text-white hover:text-yellow-400 font-semibold">{{ $paket->nama_paket }}</a>
                                        <p class="text-gray-400 text-xs">{{ $paket->kode_soal }}</p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        <span class="px-2 py-1 font-semibold leading-tight text-yellow-200 bg-yellow-900/50 rounded-full">{{ Str::ucfirst($paket->tipe_paket) }}</span>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        @if ($paket->status == 'published')
                                            <span class="px-2 py-1 font-semibold leading-tight text-green-200 bg-green-900/50 rounded-full">Published</span>
                                        @else
                                            <span class="px-2 py-1 font-semibold leading-tight text-gray-400 bg-gray-600 rounded-full">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        @if ($paket->tipe_paket == 'event')
                                            <div x-data="countdownTimer({{ $paket->waktu_mulai_timestamp ?? 'null' }}, {{ $paket->waktu_selesai_timestamp ?? 'null' }}, {{ $paket->server_now_timestamp ?? 'null' }})" x-init="init()" class="relative" @mouseenter="tooltip = true" @mouseleave="tooltip = false">
                                                <p class="text-gray-300">{{ \Carbon\Carbon::parse($paket->waktu_mulai)->format('d M Y, H:i') }}</p>
                                                <div x-show="tooltip" x-transition class="absolute z-10 -top-12 left-0 p-2 text-xs text-white bg-gray-800 rounded-md shadow-lg whitespace-nowrap">
                                                    <p x-text="status"></p>
                                                    <p class="font-mono" x-text="countdownText"></p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                        <div class="flex items-center space-x-3">
                                            {{-- Menambahkan tombol Analisis baru --}}
                                            <a href="{{ route('paket-tryout.analysis', $paket->id) }}" class="text-blue-400 hover:text-blue-300 font-medium">Analisis</a>
                                            <a href="{{ route('paket-tryout.show', $paket->id) }}" class="text-yellow-400 hover:text-yellow-300 font-medium">Detail</a>
                                            <form method="POST" action="{{ route('paket-tryout.toggle_status', $paket->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="font-medium {{ $paket->status == 'published' ? 'text-red-400 hover:text-red-300' : 'text-green-400 hover:text-green-300' }}">
                                                    {{ $paket->status == 'published' ? 'Unpublish' : 'Publish' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('paket-tryout.destroy', $paket->id) }}" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada paket tryout.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
