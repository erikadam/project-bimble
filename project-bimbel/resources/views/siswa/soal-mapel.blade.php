<x-guest-layout>
    {{-- Inisialisasi Alpine.js untuk timer dan modal --}}
    <div class="py-12 bg-gray-900" x-data="ujianPageData({{ $sisaWaktu }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="font-semibold text-xl text-white leading-tight">
                                {{ __('Ujian') }}: {{ $mapel->nama_mapel }}
                            </h2>
                            <p class="text-sm text-gray-400">Paket Ujian: <span class="font-medium">{{ $paketTryout->nama_paket }}</span></p>
                            <p class="text-sm text-gray-400">Peserta: <span class="font-medium">{{ $student->nama_lengkap }} ({{ $student->kelompok }})</span></p>
                        </div>
                        {{-- Timer utama yang dikontrol oleh Alpine.js --}}
                        <div id="timer" class="text-xl font-bold text-yellow-400" x-text="timerText"></div>
                    </div>
                    <hr class="my-4 border-gray-700">
                    <form id="ujian-form" method="POST" action="{{ route('siswa.ujian.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                        @csrf

                        <div class="space-y-8">
                            @forelse ($mapel->soal as $soal)
                                <div class="p-4 border border-gray-700 bg-gray-700 rounded-lg shadow-sm">
                                    <div class="font-bold text-lg mb-4 text-white">{!! $loop->iteration . '. ' . $soal->pertanyaan !!}</div>

                                    @if ($soal->tipe_soal === 'pilihan_ganda')
                                        <div class="space-y-2">
                                            @foreach ($soal->pilihanJawaban as $pilihan)
                                                <label class="flex items-start space-x-2 p-2 border border-gray-600 rounded-md cursor-pointer hover:bg-gray-600">
                                                    <input type="radio" name="jawaban_soal[{{ $soal->id }}]" value="{{ $pilihan->pilihan_teks }}" class="mt-1 text-yellow-400 focus:ring-yellow-400 bg-gray-800 border-gray-600">
                                                    <div>
                                                        @if ($pilihan->gambar_path)
                                                            <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs max-h-40 mb-2 rounded-lg shadow-sm object-contain">
                                                        @endif
                                                        <span class="text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif ($soal->tipe_soal === 'pilihan_ganda_majemuk')
                                        <div class="space-y-2">
                                            @foreach ($soal->pilihanJawaban as $pilihan)
                                                <label class="flex items-start space-x-2 p-2 border border-gray-600 rounded-md cursor-pointer hover:bg-gray-600">
                                                    <input type="checkbox" name="jawaban_soal[{{ $soal->id }}][]" value="{{ $pilihan->pilihan_teks }}" class="mt-1 text-yellow-400 focus:ring-yellow-400 rounded bg-gray-800 border-gray-600">
                                                    <div>
                                                        @if ($pilihan->gambar_path)
                                                            <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs max-h-40 mb-2 rounded-lg shadow-sm object-contain">
                                                        @endif
                                                        <span class="text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif ($soal->tipe_soal === 'isian')
                                        <x-text-input type="text" name="jawaban_soal[{{ $soal->id }}]" class="w-full mt-2 bg-gray-700 border-gray-600 text-white placeholder-gray-500" placeholder="Jawaban isian" />
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Tidak ada soal untuk mata pelajaran ini.</p>
                            @endforelse
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" @click.prevent="showConfirmModal = true" class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-yellow-900 uppercase tracking-widest hover:bg-yellow-500">
                                {{ $mapelSelanjutnyaId ? __('Lanjut ke Mata Pelajaran Selanjutnya') : __('Selesai Ujian') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showConfirmModal" @keydown.escape.window="showConfirmModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.outside="showConfirmModal = false" class="bg-gray-800 text-gray-200 rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-white">Konfirmasi</h3>
                <p class="mt-2 text-sm text-gray-400">
                    Anda yakin ingin menyelesaikan sesi ini? Anda tidak akan bisa kembali ke mata pelajaran ini.
                </p>
                {{-- HITUNG MUNDUR DI DALAM POPUP --}}
                <p class="mt-4 text-sm text-gray-300">
                    Sisa waktu Anda: <span class="font-bold text-yellow-400" x-text="timerText.replace('Waktu: ', '')"></span>
                </p>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" form="ujian-form" class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-md hover:bg-yellow-500">
                        Yakin & Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ujianPageData(sisaWaktuAwal) {
            return {
                showConfirmModal: false,
                waktuSisa: sisaWaktuAwal,
                timerText: 'Memuat...',

                init() {
                    const form = document.getElementById('ujian-form');

                    const countdown = setInterval(() => {
                        if (this.waktuSisa < 0) {
                            clearInterval(countdown);
                            this.timerText = 'Waktu Habis!';
                            if (form) form.submit();
                            return;
                        }

                        const minutes = Math.floor(this.waktuSisa / 60);
                        const seconds = Math.floor(this.waktuSisa % 60);

                        this.timerText = `Waktu: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                        this.waktuSisa--;
                    }, 1000);
                }
            }
        }
    </script>
</x-guest-layout>
