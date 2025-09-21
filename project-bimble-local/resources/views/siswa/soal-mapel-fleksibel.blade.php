<x-guest-layout>
    <div class="py-12 bg-gray-900" x-data="ujianPageData({{ $waktuSelesaiTimestamp }})" x-init="init()">

        {{-- Timer Sticky --}}
        <div class="sticky top-0 z-50 bg-gray-900 shadow-lg">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 px-4 sm:px-0">
                    <div>
                        <h2 class="font-semibold text-lg text-white leading-tight truncate">{{ $mapel->nama_mapel }}</h2>
                    </div>
                    <div class="flex items-center">
                        {{-- Indikator Auto-Save --}}
                        <span x-show="isSaving" class="text-sm text-gray-400 mr-4 italic" style="display: none;">Menyimpan...</span>
                        <span x-show="!isSaving && justSaved" class="text-sm text-green-400 mr-4" style="display: none;">Tersimpan!</span>
                        <div class="text-xl font-bold text-yellow-400" x-text="timerText"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">

            <form id="ujian-form" method="POST" action="{{ route('siswa.ujian.fleksibel.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                @csrf
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-200">
                        <div class="space-y-8">
                            @php
                                $jawabanTersimpanMap = $jawabanTersimpan->keyBy('soal_id');
                            @endphp

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
                                                @switch ($soal->tipe_soal)
                                                    @case('pilihan_ganda_majemuk')
                                                        @php
                                                            $jawabanSiswaArr = isset($jawabanTersimpanMap[$soal->id]) ? json_decode($jawabanTersimpanMap[$soal->id]->jawaban_peserta, true) : [];
                                                        @endphp
                                                        <p class="text-sm text-gray-400">Pilih satu atau lebih jawaban yang benar.</p>
                                                        @foreach ($soal->pilihanJawaban as $pilihan)
                                                            <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 cursor-pointer">
                                                                <input type="checkbox"
                                                                       class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500 rounded"
                                                                       name="jawaban_soal[{{ $soal->id }}][]"
                                                                       value="{{ $pilihan->pilihan_teks }}"
                                                                       @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                       {{ is_array($jawabanSiswaArr) && in_array($pilihan->pilihan_teks, $jawabanSiswaArr) ? 'checked' : '' }}>
                                                                <span class="ml-3 text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                            </label>
                                                        @endforeach
                                                        @break

                                                    @case('isian')
                                                        @php
                                                            $jawabanSiswa = $jawabanTersimpanMap[$soal->id]->jawaban_peserta ?? '';
                                                        @endphp
                                                        <input type="text"
                                                               name="jawaban_soal[{{ $soal->id }}]"
                                                               class="w-full bg-gray-800 border-gray-600 rounded-md focus:ring-yellow-500 focus:border-yellow-500 text-white"
                                                               placeholder="Jawaban Anda..."
                                                               @input.debounce.1000ms="handleAutoSave($event.target, {{ $soal->id }})"
                                                               value="{{ $jawabanSiswa }}">
                                                        @break

                                                    @case('pilihan_ganda_kompleks')
                                                        @php
                                                            $jawabanSiswaArr = isset($jawabanTersimpanMap[$soal->id]) ? json_decode($jawabanTersimpanMap[$soal->id]->jawaban_peserta, true) : [];
                                                        @endphp
                                                        <div class="overflow-x-auto p-1 bg-gray-900/50 rounded-lg">
                                                            <table class="min-w-full text-sm">
                                                                {{-- Tabel header --}}
                                                                <tbody class="divide-y divide-gray-700">
                                                                    @foreach ($soal->pernyataans as $pernyataan)
                                                                        <tr class="pernyataan-row">
                                                                            <td class="p-2 align-top text-gray-300">{!! $pernyataan->pernyataan_teks !!}</td>
                                                                            @foreach ($pernyataan->pilihanJawabans as $pilihan)
                                                                                @php
                                                                                    $isChecked = (isset($jawabanSiswaArr[$pernyataan->id]) && $jawabanSiswaArr[$pernyataan->id] == $pilihan->id);
                                                                                @endphp
                                                                                <td class="p-2 text-center align-middle">
                                                                                    <label class="flex items-center justify-center cursor-pointer">
                                                                                        <input type="radio"
                                                                                               class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500"
                                                                                               name="jawaban_soal[{{ $soal->id }}][{{ $pernyataan->id }}]"
                                                                                               value="{{ $pilihan->id }}"
                                                                                               @change="handleAutoSave($event.target, {{ $soal->id }}, '{{ $pernyataan->id }}')"
                                                                                               {{ $isChecked ? 'checked' : '' }}>
                                                                                    </label>
                                                                                </td>
                                                                            @endforeach
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @break

                                                    @default {{-- Pilihan Ganda Biasa --}}
                                                        @php
                                                            $jawabanSiswa = $jawabanTersimpanMap[$soal->id]->jawaban_peserta ?? null;
                                                        @endphp
                                                        @foreach ($soal->pilihanJawaban as $pilihan)
                                                            <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 cursor-pointer">
                                                                <input type="radio"
                                                                       class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500"
                                                                       name="jawaban_soal[{{ $soal->id }}]"
                                                                       value="{{ $pilihan->pilihan_teks }}"
                                                                       @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                       {{ $jawabanSiswa == $pilihan->pilihan_teks ? 'checked' : '' }}>
                                                                <span class="ml-3 text-gray-300">{!! $pilihan->pilihan_teks !!}</span>
                                                            </label>
                                                        @endforeach
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" @click="showConfirmModal = true" class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-yellow-900 uppercase tracking-widest hover:bg-yellow-500">
                        {{ $mapelSelanjutnyaId ? __('Lanjut') : __('Selesai') }}
                    </button>
                </div>

            </form>
            </div>

        {{-- Modal Konfirmasi --}}
        <div x-show="showConfirmModal" @keydown.escape.window="showConfirmModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.outside="showConfirmModal = false" class="bg-gray-800 text-gray-200 rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-white">Konfirmasi</h3>
                <p class="mt-2 text-sm text-gray-400">
                    @if($mapelSelanjutnyaId)
                        Anda yakin ingin menyelesaikan sesi {{ $mapel->nama_mapel }} dan melanjutkan?
                    @else
                        Anda yakin ingin menyelesaikan seluruh ujian tryout ini?
                    @endif
                </p>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" @click="showConfirmModal = false" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-md hover:bg-gray-600">Batal</button>

                    <button type="submit" form="ujian-form" name="action" value="{{ $mapelSelanjutnyaId ? 'next' : 'finish' }}" class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-md hover:bg-yellow-500">
                        Yakin & {{ $mapelSelanjutnyaId ? 'Lanjutkan' : 'Selesai' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Alpine.js --}}
    <script>
        function ujianPageData(waktuSelesaiTimestamp) {
            return {
                showConfirmModal: false,
                timerText: 'Memuat...',
                isSaving: false,
                justSaved: false,
                init() {
                    const form = document.getElementById('ujian-form');
                    const endTime = waktuSelesaiTimestamp * 1000;

                    const updateTimerDisplay = () => {
                        const distance = endTime - new Date().getTime();
                        if (distance <= 0) {
                            clearInterval(countdownInterval);
                            this.timerText = 'Waktu Habis!';
                            if (!form.dataset.submitted) {
                                form.dataset.submitted = 'true';
                                form.submit();
                            }
                        } else {
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            this.timerText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                        }
                    };

                    updateTimerDisplay();
                    const countdownInterval = setInterval(updateTimerDisplay, 1000);
                },
                handleAutoSave(element, soalId, pernyataanId = null) {
                    let jawaban;
                    const soalContainer = element.closest('div[data-soal-id]');

                    const isMajemuk = soalContainer.querySelector('input[type="checkbox"]');
                    const isKompleks = soalContainer.querySelector('input[name*="]["]'); // Cek jika nama input mengandung kurung siku ganda

                    if (isMajemuk) {
                        const checkedCheckboxes = soalContainer.querySelectorAll(`input[name="jawaban_soal[${soalId}][]"]:checked`);
                        jawaban = Array.from(checkedCheckboxes).map(cb => cb.value);
                    } else if (isKompleks) {
                        const radioButtons = soalContainer.querySelectorAll(`input[type="radio"]`);
                        jawaban = {};
                        radioButtons.forEach(radio => {
                            // Ekstrak ID pernyataan dari nama input
                            const nameMatch = radio.name.match(/\[(\d+)\]\[(\d+)\]/);
                            if (nameMatch && nameMatch[2]) {
                                const pId = nameMatch[2];
                                const checkedRadio = soalContainer.querySelector(`input[name="jawaban_soal[${soalId}][${pId}]"]:checked`);
                                if (checkedRadio) {
                                    jawaban[pId] = checkedRadio.value;
                                }
                            }
                        });
                    } else { // Isian atau Pilihan Ganda Biasa
                        jawaban = element.value;
                    }
                    this.doAutoSave(soalId, jawaban);
                },
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
