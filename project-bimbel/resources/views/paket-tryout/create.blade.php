<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Paket Tryout Baru') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="formTryout()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <div>
                        <x-input-label for="jenjang_pendidikan" :value="__('Langkah 1: Pilih Jenjang Pendidikan')" />
                        <select id="jenjang_pendidikan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="window.location.href = this.value">
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
                                <x-input-label for="nama_paket" :value="__('Langkah 2: Isi Detail Paket')" />
                                <x-text-input id="nama_paket" name="nama_paket" type="text" class="mt-1 block w-full" placeholder="Nama Paket / Ulangan" :value="old('nama_paket')" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="tipe_paket" :value="__('Tipe')" />
                                <select id="tipe_paket" name="tipe_paket" x-model="tipePaket" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="tryout">Tryout Fleksibel</option>
                                    <option value="ulangan">Ulangan</option>
                                    <option value="event">Tryout Event (Terjadwal)</option>
                                </select>
                            </div>
                            <div x-show="tipePaket === 'event'" x-transition>
                                <x-input-label for="waktu_mulai" :value="__('Waktu Mulai Event')" />
                                <x-text-input id="waktu_mulai" name="waktu_mulai" type="datetime-local" class="mt-1 block w-full" :value="old('waktu_mulai')" />
                                <p class="text-xs text-gray-500 mt-1">Siswa hanya bisa memulai ujian setelah waktu ini.</p>
                            </div>
                             <div x-show="tipePaket !== 'event'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="min_wajib" :value="__('Minimal Mata Pelajaran Wajib')" />
                                    <x-text-input id="min_wajib" name="min_wajib" type="number" class="mt-1 block w-full" placeholder="Contoh: 3" :value="old('min_wajib')" />
                                </div>
                                <div>
                                    <x-input-label for="max_opsional" :value="__('Maksimal Mata Pelajaran Opsional')" />
                                    <x-text-input id="max_opsional" name="max_opsional" type="number" class="mt-1 block w-full" placeholder="Contoh: 2" :value="old('max_opsional')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>

                        <hr class="my-6">

                        <h3 class="text-lg font-semibold">Langkah 3: Pilih Mata Pelajaran</h3>
                        <div class="mt-4 space-y-4">
                            @forelse ($mataPelajaran as $mapel)
                                <label class="p-4 border rounded-lg hover:bg-gray-50 flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" value="{{ $mapel->id }}" x-model="selectedMapelIds" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="font-medium text-gray-800">{{ $mapel->nama_mapel }} ({{ $mapel->soal->count() }} soal tersedia)</span>
                                </label>
                            @empty
                                <div class="text-center text-gray-500 py-10 border rounded-lg">
                                    <p class="font-semibold">Belum ada mata pelajaran untuk jenjang {{ $jenjang }}.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" @click="openSoalModal()" :disabled="selectedMapelIds.length === 0" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 disabled:opacity-25">
                                Lanjut Atur Soal & Durasi
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="showModal" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.outside="showModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold">Langkah 4: Atur Urutan, Soal, dan Durasi</h3>
                    <p x-show="tipePaket === 'event'" class="text-sm text-gray-600 mt-1">Seret dan lepas untuk mengubah urutan pengerjaan mata pelajaran.</p>
                </div>
                <div class="p-6 overflow-y-auto space-y-6" id="mapel-sortable-container">
                    <template x-for="(mapel, index) in selectedMapels" :key="mapel.id">
                        <div class="p-4 border rounded-lg" :data-id="mapel.id">
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
                <div class="p-4 bg-gray-50 border-t rounded-b-lg text-right">
                    <button type="button" @click="showModal = false" class="mr-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="button" @click="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan Paket Tryout</button>
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
