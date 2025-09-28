<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Buat Paket Tryout Baru') }}
        </h2>
    </x-slot>

    {{-- DIUBAH: Memindahkan style ke dalam tag <head> melalui slot untuk praktik yang lebih baik --}}
    @push('styles')
    <style>
        /* Style untuk input waktu agar lebih menarik */
        #waktu_mulai {
            background-color: #374151;
            color: #e5e7eb;
            border-color: #4b5563;
            cursor: pointer;
        }
        #waktu_mulai::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
    </style>
    @endpush

    <div class="py-12" x-data="formTryoutData()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">

                    {{-- DIHAPUS: Div pembungkus <select> jenjang yang lama dihapus --}}

                    {{-- DIUBAH: ID form diubah menjadi 'paket-tryout-form' agar cocok dengan script --}}
                    <form id="paket-tryout-form" @submit.prevent="submitForm" action="{{ route('paket-tryout.store') }}" method="POST">
                        @csrf
                        {{-- DITAMBAHKAN: Input hidden untuk mengirim jenjang yang dipilih dari filter --}}
                        <input type="hidden" name="jenjang_pendidikan" :value="selectedJenjang">

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="nama_paket" :value="__('Langkah 1: Isi Detail Paket')" class="text-gray-300" />
                                <x-text-input id="nama_paket" name="nama_paket" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200" placeholder="Nama Paket" :value="old('nama_paket')" required autofocus />
                            </div>
                            <div>
                                <x-input-label for="tipe_paket" :value="__('Tipe')" class="text-gray-300"/>
                                <select id="tipe_paket" name="tipe_paket" x-model="tipePaket" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="tryout">Tryout Fleksibel</option>
                                    <option value="event">Tryout Event (Terjadwal)</option>
                                    <option value="pacu">Tryout Pacu</option>
                                </select>
                            </div>
                            <div x-show="tipePaket === 'event' || tipePaket === 'pacu'" x-transition style="display: none;">
                                <x-input-label for="waktu_mulai" :value="__('Waktu Mulai Ujian')" class="text-gray-300"/>
                                {{-- DITAMBAHKAN: Label pembungkus agar seluruh area input waktu bisa diklik --}}
                                <label class="block mt-1 cursor-pointer">
                                    <x-text-input id="waktu_mulai" name="waktu_mulai" type="datetime-local" class="block w-full" :value="old('waktu_mulai')" />
                                </label>
                                <p class="text-xs text-gray-400 mt-1">Siswa hanya bisa memulai ujian setelah waktu ini.</p>
                            </div>
                             <div x-show="tipePaket !== 'ulangan'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        <h3 class="text-lg font-semibold text-gray-200">Langkah 2: Pilih Mata Pelajaran</h3>

                        {{-- DITAMBAHKAN: Area filter dinamis untuk jenjang dan kelas --}}
                        <div class="flex items-end space-x-4 p-4 my-4 bg-gray-900/50 rounded-lg">
                            <div class="flex-1">
                                <label for="filter_jenjang" class="text-sm font-medium text-gray-300">Filter Jenjang</label>
                                <select id="filter_jenjang" x-model="selectedJenjang" @change="updateKelasFilter(); filterMapel()" class="block w-full mt-1 bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label for="filter_kelas" class="text-sm font-medium text-gray-300">Filter Kelas</label>
                                <select id="filter_kelas" x-model="selectedKelas" @change="filterMapel()" class="block w-full mt-1 bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" :disabled="!selectedJenjang">
                                    <option value="">-- Semua Kelas --</option>
                                    <template x-for="k in kelasFilterOptions" :key="k">
                                        <option :value="k" x-text="k"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- DIUBAH: Daftar mata pelajaran kini dinamis sesuai filter --}}
                        <div class="mt-4 space-y-4">
                            <template x-if="filteredMataPelajaran.length === 0">
                                <div class="text-center text-gray-500 py-10 border border-dashed border-gray-700 rounded-lg">
                                    <p class="font-semibold" x-show="!selectedJenjang">Pilih Jenjang pada filter di atas untuk memulai.</p>
                                    <p class="font-semibold" x-show="selectedJenjang">Tidak ada mata pelajaran yang cocok.</p>
                                </div>
                            </template>

                            <template x-for="mapel in filteredMataPelajaran" :key="mapel.id">
                                <label class="p-4 border border-gray-700 rounded-lg hover:bg-gray-700 flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" :value="mapel.id" x-model="selectedMapelIds" class="rounded border-gray-600 bg-gray-800 text-yellow-500 shadow-sm focus:ring-yellow-500 w-5 h-5">
                                    <div>
                                        <span class="font-medium text-gray-200" x-text="mapel.nama_mapel"></span>
                                        <span class="block text-xs text-gray-400" x-text="`${mapel.soal.length} soal tersedia | Kelas ${mapel.kelas}`"></span>
                                    </div>
                                </label>
                            </template>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" @click="openSoalModal()" :disabled="selectedMapelIds.length === 0" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400 disabled:opacity-25">
                                Lanjut Atur Soal & Durasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Popup Pengaturan Durasi & Soal --}}
        <div x-show="showModal" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;" x-transition>
            <div @click.outside="showModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-100">Langkah 3: Atur Soal & Durasi</h3>
                        <p x-show="tipePaket === 'event'" class="text-sm text-gray-400 mt-1">Seret dan lepas untuk mengubah urutan.</p>
                    </div>
                    {{-- DITAMBAHKAN: Tampilan Total Durasi --}}
                    <div class="text-right">
                        <span class="text-sm text-gray-400 block">Total Durasi</span>
                        <span class="text-2xl font-bold text-yellow-400" x-text="totalDurasi + ' menit'"></span>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto space-y-4" id="mapel-sortable-container">
                    <template x-for="(mapel, index) in selectedMapels" :key="mapel.id">
                        <div class="bg-gray-900/50 border border-gray-700 rounded-lg" :data-id="mapel.id">
                            {{-- DITAMBAHKAN: Fitur Accordion (Buka/Tutup) --}}
                            <div @click="toggleAccordion(mapel.id)" class="p-4 flex justify-between items-center cursor-pointer hover:bg-gray-700/50">
                                <div class="flex items-center">
                                    <svg x-show="tipePaket === 'event'" class="w-6 h-6 mr-3 text-gray-500 cursor-grab" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                                    <div>
                                        <h4 class="font-bold text-lg text-gray-100" x-text="`${tipePaket === 'event' ? (index + 1) + '. ' : ''}${mapel.nama_mapel}`"></h4>
                                        <p class="text-sm text-yellow-400"><span x-text="mapel.selectedSoal.length"></span> dari <span x-text="mapel.soal.length"></span> soal dipilih</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="w-32">
                                        <x-input-label :value="__('Durasi (menit)')" class="text-sm text-gray-300 mb-1 text-right"/>
                                        <x-text-input type="number" @click.stop x-model.number="mapel.durasi" min="1" class="w-full text-right bg-gray-700 border-gray-600 text-gray-200" />
                                    </div>
                                    <svg class="w-6 h-6 text-gray-400 transition-transform" :class="{'rotate-180': openAccordions.includes(mapel.id)}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            <div x-show="openAccordions.includes(mapel.id)" x-transition class="max-h-60 overflow-y-auto border-t border-gray-700 py-2 px-4 space-y-2">
                                <label class="flex items-center space-x-2 sticky top-0 bg-gray-800 py-1.5 z-10">
                                    <input type="checkbox" @click="toggleAllSoal(mapel)" :checked="mapel.selectedSoal.length === mapel.soal.length && mapel.soal.length > 0" class="rounded bg-gray-700 border-gray-600 text-yellow-500 w-5 h-5">
                                    <span class="text-sm font-semibold text-gray-200">Pilih Semua Soal</span>
                                </label>
                                <template x-for="s in mapel.soal" :key="s.id">
                                    <div class="flex items-start justify-between p-2 rounded hover:bg-gray-700/50">
                                        <label class="flex items-start space-x-2 flex-1 cursor-pointer">
                                            <input type="checkbox" :value="s.id" x-model="mapel.selectedSoal" class="rounded bg-gray-700 border-gray-600 text-yellow-500 mt-1 w-5 h-5">
                                            <span class="text-sm text-gray-300 flex-1" x-html="s.pertanyaan.replace(/<[^>]+>/g, '').substring(0, 120) + '...'"></span>
                                        </label>
                                        <button type="button" @click.stop="openSoalDetailModal(s)" class="ml-4 text-gray-500 hover:text-blue-400" title="Lihat Detail Soal">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="p-4 bg-gray-900 border-t border-gray-700 rounded-b-lg flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-500">Batal</button>
                    <button type="button" @click="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500">Simpan Paket Tryout</button>
                </div>
            </div>
        </div>

        {{-- Popup untuk Detail Soal --}}
        <div x-show="soalDetailModal" @keydown.escape.window="soalDetailModal = false" class="fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-75" style="display: none;" x-transition>
            <div @click.outside="soalDetailModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[80vh] flex flex-col">
                <div class="p-4 border-b border-gray-700"><h3 class="font-bold text-gray-100">Detail Soal</h3></div>
                <div class="p-6 overflow-y-auto prose prose-invert max-w-none" x-html="selectedSoal.pertanyaan"></div>
                <div class="p-4 bg-gray-900 border-t border-gray-700 rounded-b-lg flex justify-end">
                    <button type="button" @click="soalDetailModal = false" class="px-4 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-500">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        function formTryoutData() {
            return {
                showModal: false,
                tipePaket: 'tryout',
                allMapels: @json($mataPelajaran),
                selectedMapelIds: [],
                selectedMapels: [],
                sortable: null,
                selectedJenjang: '{{ $jenjang ?? '' }}',
                selectedKelas: '',
                kelasFilterOptions: [],
                filteredMataPelajaran: [],
                soalDetailModal: false,
                selectedSoal: { pertanyaan: '' },
                openAccordions: [],

                get totalDurasi() {
                    return this.selectedMapels.reduce((total, mapel) => total + (parseInt(mapel.durasi) || 0), 0);
                },

                init() {
                    this.updateKelasFilter();
                    this.filterMapel();

                    // DITAMBAHKAN: Event listener untuk klik input waktu
                    document.addEventListener('DOMContentLoaded', () => {
                        const dateTimeInput = document.getElementById('waktu_mulai');
                        if (dateTimeInput) {
                             dateTimeInput.parentElement.addEventListener('click', () => {
                                try {
                                    dateTimeInput.showPicker();
                                } catch (error) {
                                    console.error("Fungsi showPicker() tidak didukung oleh browser ini.", error);
                                }
                            });
                        }
                    });
                },

                updateKelasFilter() {
                    if (this.selectedJenjang) {
                        this.kelasFilterOptions = [...new Set(this.allMapels.filter(m => m.jenjang_pendidikan === this.selectedJenjang).map(m => m.kelas))].sort((a,b) => a-b);
                    } else {
                        this.kelasFilterOptions = [];
                    }
                    this.selectedKelas = '';
                    this.selectedMapelIds = [];
                },

                filterMapel() {
                    if (!this.selectedJenjang) {
                        this.filteredMataPelajaran = [];
                        return;
                    }
                    this.filteredMataPelajaran = this.allMapels.filter(mapel => {
                        const jenjangMatch = mapel.jenjang_pendidikan === this.selectedJenjang;
                        const kelasMatch = !this.selectedKelas || mapel.kelas == this.selectedKelas;
                        return jenjangMatch && kelasMatch;
                    });
                },

                toggleAccordion(mapelId) {
                    const index = this.openAccordions.indexOf(mapelId);
                    if (index === -1) { this.openAccordions.push(mapelId); }
                    else { this.openAccordions.splice(index, 1); }
                },

                openSoalModal() {
                this.selectedMapels = this.allMapels
                    .filter(mapel => this.selectedMapelIds.includes(String(mapel.id)))
                    .map(mapel => ({
                        id: mapel.id,
                        nama_mapel: mapel.nama_mapel,
                        soal: mapel.soal,
                        selectedSoal: mapel.soal.map(s => s.id),
                        durasi: 15,
                        is_wajib: mapel.is_wajib // Bawa properti is_wajib
                    }))
                    // Urutkan sekali lagi di dalam popup
                    .sort((a, b) => b.is_wajib - a.is_wajib);
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
                    if (mapel.selectedSoal.length === mapel.soal.length) { mapel.selectedSoal = []; }
                    else { mapel.selectedSoal = mapel.soal.map(s => s.id); }
                },

                openSoalDetailModal(soal) {
                    this.selectedSoal = soal;
                    this.soalDetailModal = true;
                },

                submitForm() {
                    if (!this.selectedJenjang) {
                        alert('Harap pilih jenjang pada filter terlebih dahulu.');
                        return;
                    }
                    const form = document.getElementById('paket-tryout-form');
                    // Hapus input lama jika ada untuk mencegah duplikasi
                    form.querySelectorAll('input[name^="mata_pelajaran"]').forEach(el => el.remove());

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
