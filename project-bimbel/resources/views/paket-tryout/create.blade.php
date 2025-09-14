<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Buat Paket Tryout Baru') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="formTryout()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">
                    <div>
                        <x-input-label for="jenjang_pendidikan" :value="__('Langkah 1: Pilih Jenjang Pendidikan')" class="text-gray-300" />
                        <select id="jenjang_pendidikan" class="block mt-1 w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" onchange="window.location.href = this.value">
                            <option value="{{ route('paket-tryout.create') }}">-- Pilih Jenjang --</option>
                            @foreach (['SD', 'SMP', 'SMA'] as $jenjangOption)
                                <option value="{{ route('paket-tryout.create', ['jenjang' => $jenjangOption]) }}" @if($jenjang === $jenjangOption) selected @endif>
                                    {{ $jenjangOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if ($jenjang)
                    <form id="paket-tryout-form" method="POST" action="{{ route('paket-tryout.store') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="jenjang_pendidikan" value="{{ $jenjang }}">

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="nama_paket" :value="__('Langkah 2: Isi Detail Paket')" class="text-gray-300" />
                                <x-text-input id="nama_paket" name="nama_paket" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" placeholder="Nama Paket / Ulangan" :value="old('nama_paket')" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="tipe_paket" :value="__('Tipe')" class="text-gray-300"/>
                                <select id="tipe_paket" name="tipe_paket" x-model="tipePaket" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="tryout">Tryout Fleksibel</option>
                                    <option value="ulangan">Ulangan</option>
                                    <option value="event">Tryout Event (Terjadwal)</option>
                                </select>
                            </div>
                            <div x-show="tipePaket === 'event'" x-transition>
                                <x-input-label for="waktu_mulai" :value="__('Waktu Mulai Event')" class="text-gray-300"/>
                                <x-text-input id="waktu_mulai" name="waktu_mulai" type="datetime-local" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" :value="old('waktu_mulai')" />
                                <p class="text-xs text-gray-400 mt-1">Siswa hanya bisa memulai ujian setelah waktu ini.</p>
                            </div>
                             <div x-show="tipePaket !== 'event'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="min_wajib" :value="__('Minimal Mata Pelajaran Wajib')" class="text-gray-300"/>
                                    <x-text-input id="min_wajib" name="min_wajib" type="number" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" placeholder="Contoh: 3" :value="old('min_wajib')" />
                                </div>
                                <div>
                                    <x-input-label for="max_opsional" :value="__('Maksimal Mata Pelajaran Opsional')" class="text-gray-300"/>
                                    <x-text-input id="max_opsional" name="max_opsional" type="number" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" placeholder="Contoh: 2" :value="old('max_opsional')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" class="text-gray-300"/>
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-600">

                        <h3 class="text-lg font-semibold text-gray-200">Langkah 3: Pilih Mata Pelajaran</h3>
                        <div class="mt-4 space-y-4">
                            @forelse ($mataPelajaran as $mapel)
                                <label class="p-4 border border-gray-700 rounded-lg hover:bg-gray-700 flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $mapel->id }}" x-model="selectedMapelIds" class="rounded border-gray-600 bg-gray-800 text-yellow-500 shadow-sm focus:ring-yellow-500">
                                    <span class="font-medium text-gray-200">{{ $mapel->nama_mapel }} ({{ $mapel->soal->count() }} soal tersedia)</span>
                                </label>
                            @empty
                                <div class="text-center text-gray-500 py-10 border border-gray-700 rounded-lg">
                                    <p class="font-semibold">Belum ada mata pelajaran untuk jenjang {{ $jenjang }}.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" @click="openSoalModal()" :disabled="selectedMapelIds.length === 0" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400 disabled:opacity-25">
                                Lanjut Atur Soal & Durasi
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="showModal" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
            <div @click.outside="showModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-xl font-bold text-gray-100">Langkah 4: Atur Urutan, Soal, dan Durasi</h3>
                    <p x-show="tipePaket === 'event'" class="text-sm text-gray-400 mt-1">Seret dan lepas untuk mengubah urutan pengerjaan mata pelajaran.</p>
                </div>
                <div class="p-6 overflow-y-auto space-y-6" id="mapel-sortable-container">
                    <template x-for="(mapel, index) in selectedMapels" :key="mapel.id">
                        <div class="p-4 border border-gray-700 rounded-lg" :data-id="mapel.id">
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
                <div class="p-4 bg-gray-900 border-t border-gray-700 rounded-b-lg text-right">
                    <button type="button" @click="showModal = false" class="mr-2 px-4 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-500">Batal</button>
                    <button type="button" @click="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500">Simpan Paket Tryout</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Library untuk Drag & Drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        function formTryout() {
            return {
                showModal: false,
                tipePaket: 'tryout',
                allMapels: @json($mataPelajaran),
                selectedMapelIds: [],
                selectedMapels: [],
                sortable: null,

                openSoalModal() {
                    this.selectedMapels = this.allMapels
                        .filter(mapel => this.selectedMapelIds.includes(String(mapel.id)))
                        .map(mapel => ({
                            id: mapel.id,
                            nama_mapel: mapel.nama_mapel,
                            soal: mapel.soal,
                            selectedSoal: mapel.soal.map(s => s.id),
                            durasi: 15
                        }));
                    this.showModal = true;

                    this.$nextTick(() => {
                        if (this.tipePaket === 'event') {
                            const container = document.getElementById('mapel-sortable-container');
                            if (this.sortable) this.sortable.destroy();
                            this.sortable = new Sortable(container, {
                                animation: 150,
                                onEnd: (evt) => {
                                    const item = this.selectedMapels.splice(evt.oldIndex, 1)[0];
                                    this.selectedMapels.splice(evt.newIndex, 0, item);
                                }
                            });
                        }
                    });
                },

                toggleAllSoal(mapel) {
                    if (mapel.selectedSoal.length === mapel.soal.length) {
                        mapel.selectedSoal = [];
                    } else {
                        mapel.selectedSoal = mapel.soal.map(s => s.id);
                    }
                },

                submitForm() {
                    const form = document.getElementById('paket-tryout-form');

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
                        inputUrutan.value = index;
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
