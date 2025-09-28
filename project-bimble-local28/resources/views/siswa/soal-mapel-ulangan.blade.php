<x-guest-layout>
    <div class="py-12 bg-gray-900" x-data="ulanganPageData">
        {{-- Header Sticky --}}
        <div class="sticky top-0 z-50 bg-gray-900 shadow-lg">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 px-4 sm:px-0">
                    <div>
                        <h2 class="font-semibold text-lg text-white leading-tight truncate">{{ $ulangan->mataPelajaran->nama_mapel }}</h2>
                        <p class="text-sm text-gray-400">Ulangan: <span class="font-medium">{{ $ulangan->nama_ulangan }}</span></p>
                    </div>
                     <div class="flex items-center">
                        <span x-show="isSaving" class="text-sm text-gray-400 mr-4 italic" style="display: none;">Menyimpan...</span>
                        <span x-show="!isSaving && justSaved" class="text-sm text-green-400 mr-4" style="display: none;">Tersimpan!</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Konten Utama --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    <form id="ulangan-form" action="{{ route('siswa.ulangan.simpan', $ulangan->id) }}" method="POST">
                        @csrf
                        <div class="space-y-8">
                            @foreach ($ulangan->soal as $index => $soal)
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
                                                    $jawabanUntukSoalIni = $jawabanTersimpan->get($soal->id)->pilihan_jawaban_id ?? null;
                                                    // Untuk pilihan majemuk dan kompleks, kita perlu mengurai jawaban dari format JSON
                                                    $jawabanArray = is_string($jawabanUntukSoalIni) ? json_decode($jawabanUntukSoalIni, true) : ($jawabanUntukSoalIni ?? []);
                                                @endphp
                                                 @if ($soal->tipe_soal === 'pilihan_ganda_majemuk')
                                                    <p class="text-sm text-gray-400">Pilih satu atau lebih jawaban yang benar.</p>
                                                    @foreach ($soal->pilihanJawaban as $pilihan)
                                                        <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 cursor-pointer">
                                                            <input type="checkbox"
                                                                   class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500 rounded"
                                                                   name="jawaban_soal[{{ $soal->id }}][]"
                                                                   value="{{ $pilihan->id }}"
                                                                   @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                   @if(is_array($jawabanArray) && in_array($pilihan->id, $jawabanArray)) checked @endif>
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
                                                                                    $jawabanPernyataanIni = is_array($jawabanArray) && isset($jawabanArray[$pernyataan->id]) ? $jawabanArray[$pernyataan->id] : null;
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
                                                        <label class="flex items-center p-3 bg-gray-800 rounded-md hover:bg-gray-600 transition-colors duration-200 cursor-pointer">
                                                            <input type="radio"
                                                                   class="text-yellow-400 bg-gray-900 border-gray-700 focus:ring-yellow-500"
                                                                   name="jawaban_soal[{{ $soal->id }}]"
                                                                   value="{{ $pilihan->id }}"
                                                                   @change="handleAutoSave($event.target, {{ $soal->id }})"
                                                                   @if($jawabanUntukSoalIni == $pilihan->id) checked @endif>
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

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-yellow-900 uppercase tracking-widest hover:bg-yellow-500">
                                {{ __('Selesaikan Ulangan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ulanganPageData() {
            return {
                isSaving: false,
                justSaved: false,

                // Memperbaiki logika untuk mengumpulkan semua jawaban dari soal matriks
                handleAutoSave(element, soalId, pernyataanId = null) {
                    let jawaban;
                    const soalItem = document.querySelector(`div[data-soal-id="${soalId}"]`);

                    if (soalItem.querySelector('input[type="checkbox"]')) {
                        const checkedCheckboxes = soalItem.querySelectorAll(`input[name="jawaban_soal[${soalId}][]"]:checked`);
                        jawaban = Array.from(checkedCheckboxes).map(cb => cb.value);
                    } else if (soalItem.querySelector('input[name^="jawaban_soal[' + soalId + ']["]')) {
                        // Logika yang diperbaiki: mengumpulkan semua jawaban dari satu soal matriks
                        const radioButtons = soalItem.querySelectorAll(`input[name^="jawaban_soal[${soalId}]"][type="radio"]:checked`);
                        jawaban = {};
                        radioButtons.forEach(radio => {
                            // Ekstrak pernyataanId dari nama input
                            const match = radio.name.match(/\[(\d+)\]$/);
                            if (match) {
                                jawaban[match[1]] = radio.value;
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

                    const payload = {
                        soal_id: soalId,
                        jawaban: jawaban
                    };

                    fetch(`{{ route('siswa.ulangan.autosave', ['ulangan' => $ulangan->id]) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => {
                        this.isSaving = false;
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Respons server tidak valid.');
                    })
                    .then(data => {
                        if(data.success) {
                            this.justSaved = true;
                            setTimeout(() => this.justSaved = false, 2000);
                        } else {
                            console.error('Auto-save gagal:', data.message);
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
