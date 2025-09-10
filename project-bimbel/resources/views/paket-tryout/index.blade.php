<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Paket Tryout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
             @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                {{-- Filter & Tombol Tambah --}}
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Nama Paket</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Jadwal Event</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paketTryouts as $paket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <a href="{{ route('paket-tryout.show', $paket->id) }}" class="text-gray-900 hover:text-indigo-600 font-semibold">{{ $paket->nama_paket }}</a>
                                        <p class="text-gray-600 text-xs">{{ $paket->kode_soal }}</p>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <span class="px-2 py-1 font-semibold leading-tight text-purple-700 bg-purple-100 rounded-full">{{ Str::ucfirst($paket->tipe_paket) }}</span>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        @if ($paket->status == 'published')
                                            <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Published</span>
                                        @else
                                            <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        @if ($paket->tipe_paket == 'event')
                                            <div x-data="countdownTimer({{ $paket->waktu_mulai_timestamp ?? 'null' }}, {{ $paket->waktu_selesai_timestamp ?? 'null' }}, {{ $paket->server_now_timestamp ?? 'null' }})" x-init="init()" class="relative" @mouseenter="tooltip = true" @mouseleave="tooltip = false">
                                                <p>{{ \Carbon\Carbon::parse($paket->waktu_mulai)->format('d M Y, H:i') }}</p>
                                                <div x-show="tooltip" x-transition class="absolute z-10 -top-12 left-0 p-2 text-xs text-white bg-gray-800 rounded-md shadow-lg whitespace-nowrap">
                                                    <p x-text="status"></p>
                                                    <p class="font-mono" x-text="countdownText"></p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('paket-tryout.show', $paket->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                                            <form method="POST" action="{{ route('paket-tryout.toggle_status', $paket->id) }}"> @csrf @method('PATCH') <button type="submit" class="font-medium {{ $paket->status == 'published' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">{{ $paket->status == 'published' ? 'Unpublish' : 'Publish' }}</button> </form>
                                            <form method="POST" action="{{ route('paket-tryout.destroy', $paket->id) }}" onsubmit="return confirm('Yakin ingin menghapus?');"> @csrf @method('DELETE') <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button> </form>
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
