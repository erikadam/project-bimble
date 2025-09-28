<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Mata Pelajaran') }}
        </h2>
    </x-slot>

    {{-- Inisialisasi Alpine.js untuk mengelola semua state halaman --}}
    <div class="py-12" x-data="pageData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-800 border-l-4 border-green-500 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                 <div class="bg-red-100 dark:bg-red-800 border-l-4 border-red-500 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="mb-6 flex justify-end">
                <x-primary-button @click="isModalOpen = true">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Mata Pelajaran
                </x-primary-button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold mb-4 md:mb-0">Daftar Mata Pelajaran</h3>
                        <div class="flex items-center space-x-2 w-full md:w-auto">
                            {{-- Filter Jenjang --}}
                            <div class="flex-1">
                                <label for="filter_jenjang" class="text-sm text-gray-600 dark:text-gray-400">Jenjang:</label>
                                <select id="filter_jenjang" x-model="filterJenjang" @change="updateFilterKelasOptions(); applyFilter()" class="block w-full mt-1 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                    <option value="">Semua</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                </select>
                            </div>
                            {{-- Filter Kelas --}}
                            <div class="flex-1" x-show="filterJenjang">
                                <label for="filter_kelas" class="text-sm text-gray-600 dark:text-gray-400">Kelas:</label>
                                <select id="filter_kelas" x-model="filterKelas" @change="applyFilter()" class="block w-full mt-1 border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                    <option value="">Semua</option>
                                    <template x-for="k in filterKelasOptions" :key="k">
                                        <option :value="k" x-text="k"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Mata Pelajaran</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenjang</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($mataPelajaran as $mapel)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm">
                                        <a href="{{ route('mata-pelajaran.soal.index', $mapel->id) }}" class="text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold">
                                            {{ $mapel->nama_mapel }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-900 dark:text-white">{{ $mapel->jenjang_pendidikan }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-900 dark:text-white">{{ $mapel->kelas ?? 'N/A' }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm">
                                        @if ($mapel->is_wajib)
                                            <span class="px-2 py-1 font-semibold leading-tight text-blue-700 dark:text-blue-200 bg-blue-100 dark:bg-blue-800 rounded-full">Wajib</span>
                                        @else
                                            <span class="px-2 py-1 font-semibold leading-tight text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-600 rounded-full">Opsional</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm text-center">
                                        <div class="flex items-center justify-center space-x-3">
                                            <button @click="showDetailModal({{ json_encode($mapel) }})" class="text-gray-500 hover:text-blue-400" title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                            <a href="{{ route('mata-pelajaran.edit', $mapel->id) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-300 font-medium" title="Edit">Edit</a>
                                            <form method="POST" action="{{ route('mata-pelajaran.destroy', $mapel->id) }}" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-300 font-medium" title="Hapus">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada data mata pelajaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.away="isModalOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4">
                {{-- Kode form di dalam modal tambah TIDAK BERUBAH --}}
                <form action="{{ route('mata-pelajaran.store') }}" method="POST" class="p-6">
                    @csrf
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Tambah Mata Pelajaran Baru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="modal_nama_mapel" :value="__('Nama Mata Pelajaran')" />
                            <x-text-input type="text" id="modal_nama_mapel" name="nama_mapel" class="w-full mt-1" placeholder="Contoh: Fisika" required :value="old('nama_mapel')" />
                        </div>
                        <div>
                            <x-input-label for="modal_jenjang_pendidikan" :value="__('Jenjang Pendidikan')" />
                            <select name="jenjang_pendidikan" id="modal_jenjang_pendidikan" x-model="formJenjang" @change="updateFormKelasOptions()" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                        </div>
                        <div x-show="formJenjang">
                            <x-input-label for="modal_kelas" :value="__('Kelas')" />
                            <select name="kelas" id="modal_kelas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Pilih Kelas</option>
                                <template x-for="k in formKelasOptions" :key="k">
                                    <option :value="k" x-text="k" :selected="k == old('kelas')"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-700 dark:text-gray-400">Tipe:</p>
                        <div class="flex items-center space-x-4 mt-2">
                             <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                <input type="radio" name="is_wajib" value="0" class="text-indigo-600 focus:ring-indigo-500" {{ old('is_wajib', '0') == '0' ? 'checked' : '' }}>
                                <span class="ms-2">Opsional</span>
                            </label>
                            <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                <input type="radio" name="is_wajib" value="1" class="text-indigo-600 focus:ring-indigo-500" {{ old('is_wajib') == '1' ? 'checked' : '' }}>
                                <span class="ms-2">Wajib</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-secondary-button type="button" @click="isModalOpen = false">Batal</x-secondary-button>
                        <x-primary-button class="ms-4">Simpan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="isDetailModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.away="isDetailModalOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100" x-text="`Detail: ${selectedMapel.nama_mapel}`"></h3>
                <div class="space-y-3 text-gray-700 dark:text-gray-300">
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                        <span class="font-semibold">Dibuat Oleh:</span>
                        <span x-text="selectedMapel.guru ? selectedMapel.guru.name : 'N/A'"></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                        <span class="font-semibold">Tanggal Dibuat:</span>
                        <span x-text="formatDate(selectedMapel.created_at)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Terakhir Diperbarui:</span>
                        <span x-text="formatDate(selectedMapel.updated_at)"></span>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <x-secondary-button type="button" @click="isDetailModalOpen = false">Tutup</x-secondary-button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pageData() {
            return {
                isModalOpen: false,
                isDetailModalOpen: false,
                selectedMapel: {},
                formJenjang: '{{ old('jenjang_pendidikan', request('jenjang', 'SD')) }}',
                formKelasOptions: [],
                filterJenjang: '{{ $jenjang ?? '' }}',
                filterKelas: '{{ $kelas ?? '' }}',
                filterKelasOptions: [],

                init() {
                    this.updateFormKelasOptions();
                    this.updateFilterKelasOptions();
                },

                updateFormKelasOptions() {
                    if (this.formJenjang === 'SD') this.formKelasOptions = [1, 2, 3, 4, 5, 6];
                    else if (this.formJenjang === 'SMP') this.formKelasOptions = [7, 8, 9];
                    else if (this.formJenjang === 'SMA') this.formKelasOptions = [10, 11, 12];
                    else this.formKelasOptions = [];
                },

                updateFilterKelasOptions() {
                    if (this.filterJenjang === 'SD') this.filterKelasOptions = [1, 2, 3, 4, 5, 6];
                    else if (this.filterJenjang === 'SMP') this.filterKelasOptions = [7, 8, 9];
                    else if (this.filterJenjang === 'SMA') this.filterKelasOptions = [10, 11, 12];
                    else this.filterKelasOptions = [];

                    if (!this.filterKelasOptions.includes(parseInt(this.filterKelas))) {
                        this.filterKelas = '';
                    }
                },

                applyFilter() {
                    setTimeout(() => {
                        let baseUrl = '{{ route('mata-pelajaran.index') }}';
                        let params = new URLSearchParams();
                        if (this.filterJenjang) {
                            params.append('jenjang', this.filterJenjang);
                        }
                        if (this.filterKelas) {
                            params.append('kelas', this.filterKelas);
                        }
                        window.location.href = `${baseUrl}?${params.toString()}`;
                    }, 50);
                },

                showDetailModal(mapel) {
                    this.selectedMapel = mapel;
                    this.isDetailModalOpen = true;
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                }
            }
        }
    </script>
</x-app-layout>
