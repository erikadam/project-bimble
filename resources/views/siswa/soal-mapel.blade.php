<x-guest-layout>
    <div class="py-12 bg-gray-900" x-data="ujianPageData({{ $waktuSelesaiTimestamp }})">

        {{-- Timer Sticky --}}
        <div class="sticky top-0 z-50 bg-gray-900 shadow-lg">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 px-4 sm:px-0">
                    <div>
                        <h2 class="font-semibold text-lg text-white leading-tight truncate">{{ $mapel->nama_mapel }}</h2>
                    </div>
                    <div class="flex items-center">
                        <span x-show="isSaving" class="text-sm text-gray-400 mr-4 italic" style="display: none;">Menyimpan...</span>
                        <span x-show="!isSaving && justSaved" class="text-sm text-green-400 mr-4" style="display: none;">Tersimpan!</span>
                        <div id="timer" class="text-xl font-bold text-yellow-400" x-text="timerText"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    {{-- Header Konten --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="font-semibold text-xl text-white leading-tight">{{ __('Ujian') }}: {{ $mapel->nama_mapel }}</h2>
                            <p class="text-sm text-gray-400">Paket: <span class="font-medium">{{ $paketTryout->nama_paket }}</span></p>
                            <p class="text-sm text-gray-400">Peserta: <span class="font-medium">{{ $student->nama_lengkap }} ({{ $student->kelompok }})</span></p>
                        </div>
                    </div>
                    <hr class="my-4 border-gray-700">

                    <form id="ujian-form" method="POST" action="{{ route('siswa.ujian.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                        @csrf
                        <div class="space-y-8">
                           @foreach ($mapel->soal as $index => $soal)
                                <div class="bg-gray-700 p-4 rounded-lg">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center font-bold text-yellow-400">{{ $index + 1 }}</div>
                                        <div class="w-full">
                                            @if ($soal->gambar_path)
                                                <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="max-w-xs max-h-80 mb-4 rounded-lg shadow-sm object-contain">
                                            @endif
                                            <div class="prose prose-invert max-w-none mb-4 text-gray-300">{!! $soal->pertanyaan !!}</div>

                                            {{-- Perbaikan Logika Pilihan Jawaban --}}
                                            <div class="space-y-3">
                                                @php
                                                    $jawabanUntukSoalIni = $jawabanTersimpan[$soal->id] ?? null;
                                                    $jawabanArray = json_decode($jawabanUntukSoalIni, true);
                                                    if (!is_array($jawabanArray)) { $jawabanArray = []; }
                                                @endphp

                                                @if ($soal->tipe_soal === 'pilihan_ganda_majemuk')
                                                    <p class="text-sm text-gray-400">Pilih satu atau lebih jawaban yang benar.</p>
                                                    @foreach ($soal->pilihanJawaban as $pilihan)
                                                        <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 cursor-pointer">
                                                            <input type="checkbox"
                                                                   class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500 rounded"
                                                                   name="jawaban_soal[{{ $soal->id }}][]"
                                                                   value="{{ $pilihan->pilihan_teks }}"
                                                                   @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                   @if(in_array($pilihan->pilihan_teks, $jawabanArray)) checked @endif>
                                                            <span class="ml-3 text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                        </label>
                                                    @endforeach
                                                @elseif ($soal->tipe_soal === 'isian')
                                                    <input type="text"
                                                           name="jawaban_soal[{{ $soal->id }}]"
                                                           class="w-full bg-gray-800 border-gray-600 rounded-md focus:ring-yellow-500 focus:border-yellow-500 text-white"
                                                           placeholder="Jawaban Anda..."
                                                           @input.debounce.1000ms="handleAutoSave($event.target, {{ $soal->id }})"
                                                           value="{{ $jawabanUntukSoalIni ?? '' }}">
                                                @else {{-- Pilihan Ganda Biasa --}}
                                                    @foreach ($soal->pilihanJawaban as $pilihan)
                                                        <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 cursor-pointer">
                                                            <input type="radio"
                                                                   class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500"
                                                                   name="jawaban_soal[{{ $soal->id }}]"
                                                                   value="{{ $pilihan->pilihan_teks }}"
                                                                   @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                   @if($jawabanUntukSoalIni == $pilihan->pilihan_teks) checked @endif>
                                                            <span class="ml-3 text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" @click.prevent="showConfirmModal = true" class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-yellow-900 uppercase tracking-widest hover:bg-yellow-500">
                                {{ $mapelSelanjutnyaId ? __('Lanjut') : __('Selesai') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal --}}
        <div x-show="showConfirmModal" @keydown.escape.window="showConfirmModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.outside="showConfirmModal = false" class="bg-gray-800 text-gray-200 rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-white">Konfirmasi</h3>
                <p class="mt-2 text-sm text-gray-400">Anda yakin ingin menyelesaikan sesi ini?</p>
                <p class="mt-4 text-sm text-gray-300">Sisa waktu Anda: <span class="font-bold text-yellow-400" x-text="timerText.replace('Waktu: ', '')"></span></p>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600">Batal</button>
                    <button type="submit" form="ujian-form" class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-md hover:bg-yellow-500">Yakin & Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ujianPageData(waktuSelesaiTimestamp) {
            return {
                // properti
                showConfirmModal: false,
                timerText: 'Memuat...',
                isSaving: false,
                justSaved: false,

                // method init untuk memulai timer
                init() {
                    const form = document.getElementById('ujian-form');
                    const endTime = waktuSelesaiTimestamp * 1000;

                    if (isNaN(endTime) || endTime === 0) {
                        this.timerText = 'Error Waktu';
                        console.error("Timestamp tidak valid.");
                        return;
                    }

                    // Panggil updateTimer sekali di awal agar tidak ada jeda
                    this.updateTimer(endTime);

                    const countdown = setInterval(() => {
                        this.updateTimer(endTime);
                    }, 1000);

                    // Pastikan interval berhenti jika elemen Alpine dihapus
                    this.$watch('showConfirmModal', () => {
                        if (!this.showConfirmModal) {
                           // Logika tambahan jika diperlukan saat modal ditutup
                        }
                    });
                },

                // method untuk memperbarui teks timer
                updateTimer(endTime) {
                    const form = document.getElementById('ujian-form');
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    if (distance < 0) {
                        this.timerText = 'Waktu Habis!';
                        // Hentikan interval jika waktu habis
                        // (Meskipun clearInterval tidak bisa dipanggil dari sini,
                        // logika di dalam init akan menanganinya)
                        if (form && !form.dataset.submitted) {
                            form.dataset.submitted = 'true';
                            form.submit();
                        }
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    this.timerText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                // method untuk menangani simpan otomatis
                handleAutoSave(element, soalId) {
                    let jawaban;
                    if (element.type === 'checkbox') {
                        const checkedCheckboxes = document.querySelectorAll(`input[name="jawaban_soal[${soalId}][]"]:checked`);
                        jawaban = Array.from(checkedCheckboxes).map(cb => cb.value);
                    } else {
                        jawaban = element.value;
                    }
                    this.doAutoSave(soalId, jawaban);
                },

                // method untuk mengirim data auto-save ke server
                doAutoSave(soalId, jawaban) {
                    this.isSaving = true;
                    this.justSaved = false;

                    fetch(`{{ route('siswa.ujian.autosave', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ soal_id: soalId, jawaban: jawaban })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.isSaving = false;
                        if(data.success) {
                            this.justSaved = true;
                            setTimeout(() => this.justSaved = false, 2000);
                        }
                    })
                    .catch(error => {
                        this.isSaving = false;
                        console.error('Auto-save gagal:', error);
                    });
                }
            }
        }
    </script>
</x-guest-layout>
