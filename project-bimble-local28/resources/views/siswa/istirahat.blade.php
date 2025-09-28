<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-900 text-white p-4">
        <div class="w-full max-w-2xl text-center bg-gray-800 p-8 rounded-lg shadow-2xl"
             x-data="istirahatTimer({{ $sisaWaktuDetik }}, '{{ $nextUrl }}')">

            <h1 class="text-2xl font-bold text-yellow-400 mb-2">Waktu Istirahat</h1>
            <p class="text-gray-400 mb-6">Anda telah menyelesaikan seluruh mata pelajaran wajib. Sesi berikutnya akan dimulai setelah waktu istirahat berakhir.</p>

            <div class="my-8">
                <div x-text="displayText" class="text-6xl font-mono font-bold text-white"></div>
                <p class="text-sm text-gray-500 mt-2">Sesi Selanjutnya: <span class="font-semibold text-gray-400">{{ $namaMapelSelanjutnya }}</span></p>
            </div>

            <div x-show="isFinished" style="display: none;" class="mt-8">
                <p class="text-green-400">Mempersiapkan sesi berikutnya...</p>
            </div>
        </div>
    </div>

    <script>
    function istirahatTimer(sisaDetikAwal, nextUrl) {
        return {
            sisaWaktu: sisaDetikAwal,
            displayText: '',
            isFinished: false,
            init() {
                this.updateDisplay();

                const countdown = setInterval(() => {
                    if (this.sisaWaktu <= 0) {
                        clearInterval(countdown);
                        this.sisaWaktu = 0;
                        this.isFinished = true;

                        setTimeout(() => {
                            window.location.href = nextUrl;
                        }, 2000);
                    } else {
                        this.sisaWaktu--;
                    }
                    this.updateDisplay();
                }, 1000);
            },
            updateDisplay() {
                if (this.sisaWaktu < 0) return;
                const m = Math.floor(this.sisaWaktu / 60);
                const s = this.sisaWaktu % 60;
                this.displayText = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }
        }
    }
    </script>
</x-guest-layout>
