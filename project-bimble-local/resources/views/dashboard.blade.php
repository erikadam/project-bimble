<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dasbor Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-brand-dark-secondary overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Pilih menu di bawah untuk memulai.</p>
                </div>
            </div>

            {{-- KARTU NOTIFIKASI EVENT BARU --}}
            @if($upcomingEvents->isNotEmpty())
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-3">🔔 Tryout Event Akan Datang</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcomingEvents as $event)
                    <a href="{{ route('paket-tryout.show', $event->id) }}" class="block p-6 bg-yellow-500/10 border border-yellow-400/30 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
                        <div x-data="countdownTimer({{ $event->waktu_mulai_timestamp ?? 'null' }}, {{ $event->server_now_timestamp ?? 'null' }})" x-init="init()">
                            <h4 class="text-xl font-bold text-gray-100">{{ $event->nama_paket }}</h4>
                            <p class="text-sm text-yellow-300/80 mt-1">{{ \Carbon\Carbon::parse($event->waktu_mulai)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
                            <p class="text-lg font-bold text-brand-yellow mt-2" x-text="countdownText"></p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <hr class="my-8 border-gray-700">

            {{-- Menu Utama dalam Bentuk Kotak/Kartu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <a href="{{ route('mata-pelajaran.pilih_jenjang') }}" class="block p-6 bg-white dark:bg-brand-dark-secondary border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">📚</div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Kelola Bank Soal</h4>
                            <p class="text-gray-500 dark:text-gray-400">Pilih jenjang untuk mengelola soal.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('mata-pelajaran.index') }}" class="block p-6 bg-white dark:bg-brand-dark-secondary border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">📖</div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Atur Mata Pelajaran</h4>
                            <p class="text-gray-500 dark:text-gray-400">Tambah/edit daftar mata pelajaran.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('paket-tryout.index') }}" class="block p-6 bg-white dark:bg-brand-dark-secondary border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">📦</div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Kelola Paket Tryout</h4>
                            <p class="text-gray-500 dark:text-gray-400">Buat, edit, dan hapus paket ujian.</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('paket-tryout.laporan_index') }}" class="block p-6 bg-white dark:bg-brand-dark-secondary border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">📊</div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Laporan Ujian</h4>
                            <p class="text-gray-500 dark:text-gray-400">Unduh rekapitulasi hasil ujian siswa.</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('ulangan.pilihJenjang') }}" class="block p-6 bg-white dark:bg-brand-dark-secondary border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
    <div class="flex items-center">
        <div class="text-4xl mr-4">📝</div>
        <div>
            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Manajemen Ulangan</h4>
            <p class="text-gray-500 dark:text-gray-400">Buat, edit, dan kelola ulangan mandiri.</p>
        </div>
    </div>
</a>

                <a href="{{ route('users.index') }}" class="block p-6 bg-white dark:bg-brand-dark-secondary border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg hover:border-brand-yellow transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="text-4xl mr-4">👥</div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200">Kelola Pengguna</h4>
                            <p class="text-gray-500 dark:text-gray-400">Tambah atau edit akun untuk guru lain.</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <script>
        function countdownTimer(startTime, serverNow) {
            return {
                countdownText: '',
                interval: null,
                init() {
                    if (startTime === null) return;
                    let now = serverNow * 1000;
                    this.updateCountdown(now, startTime * 1000);
                    this.interval = setInterval(() => {
                        now += 1000;
                        this.updateCountdown(now, startTime * 1000);
                    }, 1000);
                },
                updateCountdown(now, start) {
                    const remaining = start - now;
                    if (remaining <= 0) {
                        this.countdownText = 'Sedang Berlangsung!';
                        clearInterval(this.interval);
                    } else {
                        this.countdownText = `Dimulai dalam: ${this.formatDuration(remaining)}`;
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
                    return parts.join(' ');
                }
            }
        }
    </script>
</x-app-layout>
