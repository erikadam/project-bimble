<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Edit Paket: {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="formTryout()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">
                    <form id="edit-paket-form" method="POST" action="{{ route('paket-tryout.update', $paketTryout->id) }}">
                        @csrf
                        @method('PATCH')

                        {{-- DATA PAKET --}}
                        <div class="space-y-6">
                             <div>
                                <x-input-label for="nama_paket" :value="__('Nama Paket / Ulangan')" class="text-gray-300"/>
                                <x-text-input id="nama_paket" name="nama_paket" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" :value="old('nama_paket', $paketTryout->nama_paket)" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="tipe_paket" :value="__('Tipe')" class="text-gray-300"/>
                                <select id="tipe_paket" name="tipe_paket" x-model="tipePaket" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="tryout">Tryout Fleksibel</option>
                                    <option value="ulangan">Ulangan</option>
                                    <option value="event">Tryout Event (Terjadwal)</option>
                                </select>
                            </div>
                            {{-- PENYESUAIAN DI SINI --}}
                            <div x-show="tipePaket === 'event'" x-transition>
                                <x-input-label for="waktu_mulai" :value="__('Waktu Mulai Event')" class="text-gray-300"/>
                                <x-text-input id="waktu_mulai" name="waktu_mulai" type="datetime-local" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" :value="old('waktu_mulai', $paketTryout->waktu_mulai ? \Carbon\Carbon::parse($paketTryout->waktu_mulai)->format('Y-m-d\TH:i') : '')" />
                                <p class="text-xs text-gray-400 mt-1">Siswa hanya bisa memulai ujian setelah waktu ini.</p>
                                @error('waktu_mulai')
                                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div x-show="tipePaket !== 'event'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="min_wajib" :value="__('Minimal Mata Pelajaran Wajib')" class="text-gray-300"/>
                                    <x-text-input id="min_wajib" name="min_wajib" type="number" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" placeholder="Contoh: 3" :value="old('min_wajib', $paketTryout->min_wajib)" />
                                </div>
                                <div>
                                    <x-input-label for="max_opsional" :value="__('Maksimal Mata Pelajaran Opsional')" class="text-gray-300"/>
                                    <x-text-input id="max_opsional" name="max_opsional" type="number" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" placeholder="Contoh: 2" :value="old('max_opsional', $paketTryout->max_opsional)" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" class="text-gray-300"/>
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">{{ old('deskripsi', $paketTryout->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-700">

                        <h3 class="text-lg font-semibold text-gray-200">Atur Soal dan Durasi Pengerjaan</h3>
                         <div class="p-6 overflow-y-auto space-y-6">
                            <template x-for="(mapel, index) in selectedMapels" :key="mapel.id">
                                <div class="p-4 border border-gray-700 rounded-lg">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center">
                                            <svg x-show="tipePaket === 'event'" class="w-6 h-6 mr-2 text-gray-500 cursor-grab" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                                            <div>
                                                <h4 class="font-bold text-lg text-gray-100" x-text="`${tipePaket === 'event' ? (index + 1) + '. ' : ''}${mapel.nama_mapel}`"></h4>
                                                <p class="text-sm text-gray-400">
                                                    <span x-text="mapel.selectedSoal.length"></span> dari <span x-text="mapel.soal.length"></span> soal dipilih
                                                </p>
                                            </div>
                                        </div>
                                        <div class="w-48">
                                            <x-input-label :value="__('Durasi (menit)')" class="text-gray-300"/>
                                            <x-text-input type="number" x-model.number="mapel.durasi" min="1" class="w-full mt-1 text-right bg-gray-700 border-gray-600 text-gray-200" />
                                        </div>
                                    </div>
                                    <div class="max-h-60 overflow-y-auto border-t border-b border-gray-700 py-2 space-y-2 px-2">
                                        <label class="flex items-center space-x-2 sticky top-0 bg-gray-800 py-1">
                                            <input type="checkbox" @click="toggleAllSoal(mapel)" :checked="mapel.selectedSoal.length === mapel.soal.length" class="rounded bg-gray-700 border-gray-600 text-yellow-500">
                                            <span class="text-sm font-semibold text-gray-200">Pilih Semua Soal</span>
                                        </label>
                                        <template x-for="s in mapel.soal" :key="s.id">
                                            <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-700">
                                                <input type="checkbox" :value="s.id" x-model="mapel.selectedSoal" class="rounded bg-gray-700 border-gray-600 text-yellow-500">
                                                <span class="text-sm text-gray-300" x-html="s.pertanyaan.substring(0, 100) + '...'"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="p-4 bg-gray-900 border-t border-gray-700 rounded-b-lg flex justify-end items-center gap-4">
                            <a href="{{ route('paket-tryout.show', $paketTryout->id) }}" class="text-sm text-gray-400 hover:text-gray-200">Batal</a>
                            <button type="button" @click="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Library untuk Drag & Drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        function formTryout() {
            return {
                allMapels: @json($mataPelajaranOptions),
                selectedMapels: [],
                // Inisialisasi tipePaket dari data yang ada
                tipePaket: @json(old('tipe_paket', $paketTryout->tipe_paket)),

                init() {
                    const paketMapels = @json($paketTryout->mataPelajaran);
                    const selectedSoal = @json($selectedSoalIds);

                    this.selectedMapels = this.allMapels
                        .filter(mapel => paketMapels.some(pm => pm.id === mapel.id))
                        .map(mapel => {
                            const paketMapel = paketMapels.find(pm => pm.id === mapel.id);
                            return {
                                id: mapel.id,
                                nama_mapel: mapel.nama_mapel,
                                soal: mapel.soal,
                                selectedSoal: mapel.soal.map(s => s.id).filter(id => selectedSoal.includes(id)),
                                durasi: paketMapel.pivot.durasi_menit,
                                urutan: paketMapel.pivot.urutan
                            }
                        })
                        .sort((a, b) => a.urutan - b.urutan); // Urutkan berdasarkan data dari pivot
                },

                toggleAllSoal(mapel) {
                    if (mapel.selectedSoal.length === mapel.soal.length) {
                        mapel.selectedSoal = [];
                    } else {
                        mapel.selectedSoal = mapel.soal.map(s => s.id);
                    }
                },

                submitForm() {
                    const form = document.getElementById('edit-paket-form');

                    this.selectedMapels.forEach((mapel, index) => {
                        let inputId = document.createElement('input');
                        inputId.type = 'hidden';
                        inputId.name = `mata_pelajaran[${index}][id]`;
                        inputId.value = mapel.id;
                        form.appendChild(inputId);

                        let inputDurasi = document.createElement('input');
                        inputDurasi.type = 'hidden';
                        inputDurasi.name = `mata_pelajaran[${index}][durasi]`;
                        inputDurasi.value = mapel.durasi;
                        form.appendChild(inputDurasi);

                        let inputUrutan = document.createElement('input');
                        inputUrutan.type = 'hidden';
                        inputUrutan.name = `mata_pelajaran[${index}][urutan]`;
                        inputUrutan.value = index; // Urutan baru berdasarkan posisi saat ini
                        form.appendChild(inputUrutan);

                        mapel.selectedSoal.forEach(soalId => {
                            let inputSoal = document.createElement('input');
                            inputSoal.type = 'hidden';
                            inputSoal.name = `mata_pelajaran[${index}][soal][]`;
                            inputSoal.value = soalId;
                            form.appendChild(inputSoal);
                        });
                    });

                    form.submit();
                }
            }
        }
    </script>
</x-app-layout>
