<x-guest-layout>
    <div class="py-12 bg-gray-900" x-data="ujianPacuRealtime({{ $waktuSelesaiTimestamp }})">
        <div class="sticky top-0 z-50 bg-gray-900 shadow-lg">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 px-4 sm:px-0">
                    <div>
                        <h2 class="font-semibold text-lg text-white leading-tight truncate">{{ $mapelBerikutnya->nama_mapel }}</h2>
                    </div>
                    <div class="flex items-center">
                        <span x-show="isSaving" class="text-sm text-gray-400 mr-4 italic" style="display: none;">Menyimpan...</span>
                        <span x-show="!isSaving && justSaved" class="text-sm text-green-400 mr-4" style="display: none;">Tersimpan ✓</span>
                        <div id="timer" class="text-xl font-bold text-yellow-400" x-text="displayText">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    <p class="text-sm text-gray-400 mb-6 text-center bg-gray-900/50 p-3 rounded-lg">
                        Fokus dan kerjakan soal ini. Anda akan otomatis diarahkan ke sesi berikutnya jika waktu telah habis.
                    </p>

                    <form id="ujian-form-pacu" method="POST" action="{{ route('siswa.ujian.pacu.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelBerikutnya->id]) }}">
                        @csrf
                        <div class="space-y-8">
                            @foreach ($soals as $index => $soal)
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
                                                    $jawabanUntukSoalIni = $jawabanTersimpan->get($soal->id);
                                                    $jawabanPeserta = $jawabanUntukSoalIni ? $jawabanUntukSoalIni->jawaban_peserta : null;
                                                    $jawabanArray = is_string($jawabanPeserta) && (json_decode($jawabanPeserta) !== null) ? json_decode($jawabanPeserta, true) : ($jawabanPeserta ?? []);
                                                @endphp

                                                @if ($soal->tipe_soal === 'pilihan_ganda_majemuk')
                                                    @foreach ($soal->pilihanJawaban as $pilihan)
                                                        <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 cursor-pointer">
                                                            <input type="checkbox"
                                                                   class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500 rounded"
                                                                   name="jawaban_soal[{{ $soal->id }}][]"
                                                                   value="{{ $pilihan->pilihan_teks }}"
                                                                   @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                   @if(is_array($jawabanArray) && in_array($pilihan->pilihan_teks, $jawabanArray)) checked @endif>
                                                            <span class="ml-3 text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                        </label>
                                                    @endforeach
                                                @elseif ($soal->tipe_soal === 'isian')
                                                    <input type="text"
                                                           name="jawaban_soal[{{ $soal->id }}]"
                                                           class="w-full bg-gray-800 border-gray-600 rounded-md focus:ring-yellow-500 focus:border-yellow-500 text-white"
                                                           placeholder="Jawaban Anda..."
                                                           @input.debounce.1000ms="handleAutoSave($event.target, {{ $soal->id }})"
                                                           value="{{ is_array($jawabanPeserta) ? '' : ($jawabanPeserta ?? '') }}">
                                                @elseif ($soal->tipe_soal === 'pilihan_ganda_kompleks')
                                                    <div class="overflow-x-auto p-1 bg-gray-900/50 rounded-lg">
                                                        <table class="min-w-full text-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th class="p-2 text-left font-medium text-gray-400">Pernyataan</th>
                                                                    @foreach ($soal->pernyataans->first()->pilihanJawabans ?? [] as $pilihan)
                                                                        <th class="p-2 text-center font-medium text-gray-400">{!! $pilihan->pilihan_teks !!}</th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($soal->pernyataans as $pernyataan)
                                                                    <tr>
                                                                        <td class="p-2 align-top text-gray-300">{!! $pernyataan->pernyataan_teks !!}</td>
                                                                        @foreach ($pernyataan->pilihanJawabans as $pilihan)
                                                                            @php
                                                                                $jawabanPernyataanIni = is_array($jawabanArray) ? ($jawabanArray[$pernyataan->id] ?? null) : null;
                                                                            @endphp
                                                                            <td class="p-2 text-center align-middle">
                                                                                <input type="radio"
                                                                                    class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500"
                                                                                    name="jawaban_soal[{{ $soal->id }}][{{ $pernyataan->id }}]"
                                                                                    value="{{ $pilihan->id }}"
                                                                                    @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                                    @if($jawabanPernyataanIni == $pilihan->id) checked @endif>
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
                                                                   @if($jawabanPeserta == $pilihan->pilihan_teks) checked @endif>
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function ujianPacuRealtime(waktuSelesaiTimestamp) {
        return {
            displayText: 'Memuat...',
            isSaving: false,
            justSaved: false,
            init() {
                const serverEndTime = waktuSelesaiTimestamp * 1000;
                const countdown = setInterval(() => {
                    const sisaWaktuMs = serverEndTime - Date.now();
                    if (sisaWaktuMs <= 500) {
                        clearInterval(countdown);
                        this.displayText = 'Waktu Habis!';
                        const form = document.getElementById('ujian-form-pacu');
                        if (form && !form.dataset.submitted) {
                            form.dataset.submitted = 'true';
                            form.submit();
                        }
                    } else {
                         this.updateDisplay(Math.round(sisaWaktuMs / 1000));
                    }
                }, 1000);
                this.updateDisplay(Math.round((serverEndTime - Date.now()) / 1000));
            },
            updateDisplay(seconds) {
                if (seconds < 0) seconds = 0;
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = seconds % 60;
                this.displayText = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            },
            handleAutoSave(element, soalId) {
                let jawaban;
                const soalContainer = element.closest('div[data-soal-id]');
                const tipeSoalInput = soalContainer.querySelector('input');

                if (tipeSoalInput.type === 'checkbox') {
                    const checkedCheckboxes = soalContainer.querySelectorAll(`input[name="jawaban_soal[${soalId}][]"]:checked`);
                    jawaban = Array.from(checkedCheckboxes).map(cb => cb.value);
                } else if (tipeSoalInput.name.includes('][')) {
                    const allRadiosInSoal = soalContainer.querySelectorAll(`input[name^="jawaban_soal[${soalId}]"]`);
                    jawaban = {};
                    allRadiosInSoal.forEach(radio => {
                        const pIdMatch = radio.name.match(/\[(\d+)\]$/);
                        if (pIdMatch && radio.checked) {
                            jawaban[pIdMatch[1]] = radio.value;
                        }
                    });
                } else {
                    jawaban = element.value;
                }
                this.doAutoSave(soalId, jawaban);
            },
            doAutoSave(soalId, jawaban) {
                this.isSaving = true;
                this.justSaved = false;
                fetch(`{{ route('siswa.ujian.pacu.autosave', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelBerikutnya->id]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        soal_id: soalId,
                        jawaban: jawaban
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.justSaved = true;
                        setTimeout(() => this.justSaved = false, 2000);
                    }
                })
                .catch(err => console.error('Auto-save error:', err))
                .finally(() => {
                    this.isSaving = false;
                });
            }
        }
    }
    </script>
</x-guest-layout>
