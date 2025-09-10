<x-guest-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Sesi Selesai</h1>
            <p class="text-gray-600 mb-6">
                Anda telah menyelesaikan mata pelajaran <span class="font-semibold">{{ $mapelSebelumnya }}</span>.
            </p>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-gray-700">Mata pelajaran selanjutnya, <span class="font-semibold">{{ $mapelSelanjutnya }}</span>, akan dimulai dalam:</p>
                <div x-data="countdownTimer({{ $waktuMulaiSelanjutnya }}, {{ now()->timestamp }})" x-init="init()" class="text-2xl font-bold text-blue-600 mt-2">
                    <span x-text="countdownText"></span>
                </div>
            </div>

            <p class="text-xs text-gray-500 mt-6">Halaman ini akan otomatis dimuat ulang saat waktunya tiba. Jangan tutup halaman ini.</p>
        </div>
    </div>

    <script>
        // Refresh halaman setelah waktu hitung mundur selesai + 2 detik buffer
        setTimeout(function() {
            window.location.reload();
        }, {{ ($waktuMulaiSelanjutnya - now()->timestamp + 2) * 1000 }});

        function countdownTimer(startTime, serverNow) {
            return {
                countdownText: '',
                interval: null,
                init() {
                    let now = serverNow * 1000;
                    this.updateCountdown(now);
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
                    const seconds = Math.floor((ms / 1000) % 60);
                    const minutes = Math.floor((ms / (1000 * 60)) % 60);
                    const hours = Math.floor((ms / (1000 * 60 * 60)) % 24);
                    const days = Math.floor(ms / (1000 * 60 * 60 * 24));
                    let parts = [];
                    if (days > 0) parts.push(`${days}h`);
                    if (hours > 0) parts.push(`${hours}j`);
                    if (minutes > 0) parts.push(`${minutes}m`);
                    if (seconds >= 0) parts.push(`${seconds}d`);
                    return parts.join(' ');
                }
            }
        }
    </script>
</x-guest-layout>
