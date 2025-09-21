<x-guest-layout>
    {{-- Inisialisasi Alpine.js dengan timestamp waktu selesai --}}
    <div class="py-12 bg-gray-900" x-data="ujianPageData({{ $waktuSelesaiTimestamp }})" x-init="init()">

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
                        {{-- Timer ini sekarang sepenuhnya dikontrol oleh Alpine.js --}}
                        <div class="text-xl font-bold text-yellow-400" x-text="timerText"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    {{-- Ganti action form ke rute fleksibel --}}
                    <form id="ujian-form" method="POST" action="{{ route('siswa.ujian.fleksibel.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                        @csrf
                        <div class="space-y-8">
                            @foreach ($mapel->soal as $index => $soal)
                                <div class="bg-gray-700 p-4 rounded-lg" data-soal-id="{{ $soal->id }}">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center font-bold text-yellow-400">{{ $index + 1 }}</div>
                                        <div class="w-full">
                                            @if ($soal->gambar_path)
                                                <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="max-w-xs max-h-80 mb-4 rounded-lg shadow-sm object-contain">
                                            @endif
                                            <div class="prose prose-invert max-w-none mb-4 text-gray-300">{!! $soal->pertanyaan !!}</div>

                                            <div class="space-y-3">
                                                @php
                                                    $jawabanUntukSoalIni = $jawabanTersimpan[$soal->id] ?? null;
                                                    $jawabanArray = is_string($jawabanUntukSoalIni) ? json_decode($jawabanUntukSoalIni, true) : ($jawabanUntukSoalIni ?? []);
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
                                                                   @if(is_array($jawabanArray) && in_array($pilihan->pilihan_teks, $jawabanArray)) checked @endif>
                                                            <span class="ml-3 text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                            @if($pilihan->gambar_path)
                                                                <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="ml-auto max-h-12 rounded-md">
                                                            @endif
                                                        </label>
                                                    @endforeach

                                                @elseif ($soal->tipe_soal === 'isian')
                                                    <input type="text"
                                                           name="jawaban_soal[{{ $soal->id }}]"
                                                           class="w-full bg-gray-800 border-gray-600 rounded-md focus:ring-yellow-500 focus:border-yellow-500 text-white"
                                                           placeholder="Jawaban Anda..."
                                                           @input.debounce.1000ms="handleAutoSave($event.target, {{ $soal->id }})"
                                                           value="{{ $jawabanUntukSoalIni ?? '' }}">

                                                @elseif ($soal->tipe_soal === 'pilihan_ganda_kompleks')
                                                        <div class="overflow-x-auto p-1 bg-gray-900/50 rounded-lg">
                                                            <table class="min-w-full text-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="p-2 text-left font-medium text-gray-400">Pernyataan</th>
                                                                        @foreach ($soal->pernyataans->first()->pilihanJawabans ?? [] as $pilihan)
                                                                            <th class="p-2 text-center font-medium text-gray-400">
                                                                                {!! $pilihan->pilihan_teks !!}
                                                                            </th>
                                                                        @endforeach
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="divide-y divide-gray-700">
                                                                    @foreach ($soal->pernyataans as $pernyataan)
                                                                        <tr class="pernyataan-row">
                                                                            <td class="p-2 align-top text-gray-300">{!! $pernyataan->pernyataan_teks !!}</td>
                                                                            @foreach ($pernyataan->pilihanJawabans as $pilihan)
                                                                                @php
                                                                                    $jawabanPernyataanIni = $jawabanArray[$pernyataan->id] ?? null;
                                                                                    $isChecked = ($jawabanPernyataanIni == $pilihan->id);
                                                                                @endphp
                                                                                <td class="p-2 text-center align-middle">
                                                                                    <label class="flex items-center justify-center cursor-pointer">
                                                                                        <input type="radio"
                                                                                            class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500"
                                                                                            name="jawaban_soal[{{ $soal->id }}][{{ $pernyataan->id }}]"
                                                                                            value="{{ $pilihan->id }}"
                                                                                            @change="handleAutoSave($event.target, {{ $soal->id }}, '{{ $pernyataan->id }}')"
                                                                                            @if($isChecked) checked @endif>
                                                                                    </label>
                                                                                </td>
                                                                            @endforeach
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

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
                                                            @if($pilihan->gambar_path)
                                                                <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="ml-auto max-h-12 rounded-md">
                                                            @endif
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
                <p class="mt-2 text-sm text-gray-400">
                    @if($mapelSelanjutnyaId)
                        Anda yakin ingin menyelesaikan sesi {{ $mapel->nama_mapel }} dan melanjutkan ke mata pelajaran berikutnya?
                    @else
                        Anda yakin ingin menyelesaikan seluruh ujian tryout ini?
                    @endif
                </p>
                <p class="mt-4 text-sm text-gray-300">Sisa waktu Anda: <span class="font-bold text-yellow-400" x-text="timerText"></span></p>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600">Batal</button>

                    @if($mapelSelanjutnyaId)
                        {{-- Gunakan form action untuk redirect ke mapel berikutnya --}}
                        <form id="lanjut-form" method="POST" action="{{ route('siswa.ujian.fleksibel.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                            @csrf
                            <input type="hidden" name="next_mapel_id" value="{{ $mapelSelanjutnyaId }}">
                            <button type="submit" class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-md hover:bg-yellow-500">
                                Yakin & Lanjutkan
                            </button>
                        </form>
                    @else
                        {{-- Gunakan form action untuk menyelesaikan ujian --}}
                        <form id="selesai-form" method="POST" action="{{ route('siswa.ujian.fleksibel.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                            @csrf
                             <input type="hidden" name="next_mapel_id" value="">
                            <button type="submit" class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-md hover:bg-yellow-500">
                                Yakin & Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Script Alpine.js FINAL yang menangani semuanya --}}
    <script>
        function ujianPageData(waktuSelesaiTimestamp) {
            return {
                showConfirmModal: false,
                timerText: 'Memuat...',
                isSaving: false,
                justSaved: false,
                countdownInterval: null,

                init() {
                    const form = document.getElementById('ujian-form');
                    // Waktu selesai diambil dari timestamp yang dikirim controller
                    const endTime = waktuSelesaiTimestamp * 1000;

                    this.updateTimer(endTime); // Panggil sekali agar timer langsung muncul

                    this.countdownInterval = setInterval(() => {
                        const distance = endTime - new Date().getTime();

                        // Beri buffer 1 detik sebelum submit
                        if (distance < 1000) {
                            clearInterval(this.countdownInterval);
                            this.timerText = 'Waktu Habis!';
                            if (!form.dataset.submitted) {
                                form.dataset.submitted = 'true';
                                form.submit(); // AUTO-SUBMIT SAAT WAKTU HABIS
                            }
                        } else {
                            this.updateTimer(endTime);
                        }
                    }, 1000);
                },

                updateTimer(endTime) {
                    const now = new Date().getTime();
                    const distance = endTime - now;
                    if (distance < 0) return;

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    this.timerText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                },

                handleAutoSave(element, soalId, pernyataanId = null) {
                    let jawaban;
                    const soalItem = document.querySelector(`div[data-soal-id="${soalId}"]`);

                    if (soalItem.querySelector('input[type="checkbox"]')) {
                        const checkedCheckboxes = soalItem.querySelectorAll(`input[name="jawaban_soal[${soalId}][]"]:checked`);
                        jawaban = Array.from(checkedCheckboxes).map(cb => cb.value);
                    } else if (soalItem.querySelector('input[name^="jawaban_soal[' + soalId + ']["]')) {
                        // Logika untuk Pilihan Ganda Kompleks (Matriks)
                        const radioButtons = soalItem.querySelectorAll(`input[name="jawaban_soal[${soalId}][${pernyataanId}]"]:checked`);
                        jawaban = {};
                        if (radioButtons.length > 0) {
                            jawaban[pernyataanId] = radioButtons[0].value;
                        }
                    } else {
                        jawaban = element.value;
                    }
                    this.doAutoSave(soalId, jawaban);
                },
                doAutoSave(soalId, jawaban) {
                    this.isSaving = true;
                    this.justSaved = false;

                    const payload = {
                        soal_id: soalId,
                        jawaban: jawaban
                    };

                    fetch(`{{ route('siswa.ujian.autosave', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
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
