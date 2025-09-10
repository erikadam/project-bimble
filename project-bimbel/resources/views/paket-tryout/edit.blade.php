<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Paket: {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="formTryout()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <form id="edit-paket-form" method="POST" action="{{ route('paket-tryout.update', $paketTryout->id) }}">
                        @csrf
                        @method('PATCH')

                        {{-- DATA PAKET --}}
                        <div class="space-y-6">
                             <div>
                                <x-input-label for="nama_paket" :value="__('Nama Paket / Ulangan')" />
                                <x-text-input id="nama_paket" name="nama_paket" type="text" class="mt-1 block w-full" :value="old('nama_paket', $paketTryout->nama_paket)" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="tipe_paket" :value="__('Tipe')" />
                                <select id="tipe_paket" name="tipe_paket" x-model="tipePaket" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="tryout">Tryout Fleksibel</option>
                                    <option value="ulangan">Ulangan</option>
                                    <option value="event">Tryout Event (Terjadwal)</option>
                                </select>
                            </div>
                            {{-- PENYESUAIAN DI SINI --}}
                            <div x-show="tipePaket === 'event'" x-transition>
                                <x-input-label for="waktu_mulai" :value="__('Waktu Mulai Event')" />
                                <x-text-input id="waktu_mulai" name="waktu_mulai" type="datetime-local" class="mt-1 block w-full" :value="old('waktu_mulai', $paketTryout->waktu_mulai ? \Carbon\Carbon::parse($paketTryout->waktu_mulai)->format('Y-m-d\TH:i') : '')" />
                                <p class="text-xs text-gray-500 mt-1">Siswa hanya bisa memulai ujian setelah waktu ini.</p>
                                @error('waktu_mulai')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div x-show="tipePaket !== 'event'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="min_wajib" :value="__('Minimal Mata Pelajaran Wajib')" />
                                    <x-text-input id="min_wajib" name="min_wajib" type="number" class="mt-1 block w-full" placeholder="Contoh: 3" :value="old('min_wajib', $paketTryout->min_wajib)" />
                                </div>
                                <div>
                                    <x-input-label for="max_opsional" :value="__('Maksimal Mata Pelajaran Opsional')" />
                                    <x-text-input id="max_opsional" name="max_opsional" type="number" class="mt-1 block w-full" placeholder="Contoh: 2" :value="old('max_opsional', $paketTryout->max_opsional)" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi', $paketTryout->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <hr class="my-6">

                        <h3 class="text-lg font-semibold">Atur Soal dan Durasi Pengerjaan</h3>
                         <div class="p-6 overflow-y-auto space-y-6">
                            <template x-for="(mapel, index) in selectedMapels" :key="mapel.id">
                                <div class="p-4 border rounded-lg">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center">
                                            <svg x-show="tipePaket === 'event'" class="w-6 h-6 mr-2 text-gray-400 cursor-grab" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                                            <div>
                                                <h4 class="font-bold text-lg" x-text="`${tipePaket === 'event' ? (index + 1) + '. ' : ''}${mapel.nama_mapel}`"></h4>
                                                <p class="text-sm text-gray-600">
                                                    <span x-text="mapel.selectedSoal.length"></span> dari <span x-text="mapel.soal.length"></span> soal dipilih
                                                </p>
                                            </div>
                                        </div>
                                        <div class="w-48">
                                            <x-input-label :value="__('Durasi (menit)')" />
                                            <x-text-input type="number" x-model.number="mapel.durasi" min="1" class="w-full mt-1 text-right" />
                                        </div>
                                    </div>
                                    <div class="max-h-60 overflow-y-auto border-t border-b py-2 space-y-2 px-2">
                                        <label class="flex items-center space-x-2 sticky top-0 bg-white py-1">
                                            <input type="checkbox" @click="toggleAllSoal(mapel)" :checked="mapel.selectedSoal.length === mapel.soal.length" class="rounded">
                                            <span class="text-sm font-semibold">Pilih Semua Soal</span>
                                        </label>
                                        <template x-for="s in mapel.soal" :key="s.id">
                                            <label class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50">
                                                <input type="checkbox" :value="s.id" x-model="mapel.selectedSoal" class="rounded">
                                                <span class="text-sm" x-html="s.pertanyaan.substring(0, 100) + '...'"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="p-4 bg-gray-50 border-t rounded-b-lg flex justify-end items-center gap-4">
                            <a href="{{ route('paket-tryout.show', $paketTryout->id) }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="button" @click="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan Perubahan</button>
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
                    // ... (logika sama seperti sebelumnya)
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
