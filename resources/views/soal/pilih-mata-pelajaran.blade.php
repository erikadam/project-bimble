<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bank Soal untuk Jenjang ') }} <span class="text-yellow-400">{{ $jenjang }}</span>
            </h2>
            <a href="{{ route('mata-pelajaran.pilih_jenjang') }}" class="text-sm text-gray-400 hover:text-white">
                &larr; Kembali ke Pilih Jenjang
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="pageData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Area Filter --}}
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">Filter berdasarkan:</span>

                    {{-- Filter Kelas --}}
                    <select id="filter_kelas" x-model="filterKelas" @change="applyFilter()" class="w-full md:w-auto block border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($availableKelas as $kelasOption)
                            <option value="{{ $kelasOption }}">Kelas {{ $kelasOption }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Tipe (Wajib/Opsional) --}}
                    <select id="filter_tipe" x-model="filterTipe" @change="applyFilter()" class="w-full md:w-auto block border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">-- Semua Tipe --</option>
                        <option value="wajib">Wajib</option>
                        <option value="opsional">Opsional</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($mataPelajaran as $mapel)
                    <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $mapel->nama_mapel }}</h3>
                                @if ($mapel->is_wajib)
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 dark:text-blue-200 bg-blue-100 dark:bg-blue-800 rounded-full">Wajib</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold leading-tight text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-600 rounded-full">Opsional</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $mapel->jenjang_pendidikan }} - Kelas {{ $mapel->kelas }}
                            </p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
                                {{ $mapel->soal_count }} soal tersedia.
                            </p>
                        </div>
                        <div class="mt-4 flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button @click="showDetailModal({{ json_encode($mapel) }})" class="text-sm text-gray-500 hover:text-blue-400">
                                Detail
                            </button>
                            <a href="{{ route('mata-pelajaran.soal.index', $mapel->id) }}" class="inline-flex items-center px-3 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-yellow-400">
                                Lihat Soal
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-lg">
                        <p class="text-gray-500 dark:text-gray-400">
                            Tidak ada mata pelajaran yang ditemukan untuk filter ini.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="isDetailModalOpen" @keydown.escape.window="isDetailModalOpen = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;" x-transition>
            <div @click.away="isDetailModalOpen = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100" x-text="`Detail: ${selectedMapel.nama_mapel}`"></h3>
                <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Ditambahkan:</span>
                        <span x-text="formatDate(selectedMapel.created_at)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-500 dark:text-gray-400">Terakhir Diubah:</span>
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
                isDetailModalOpen: false,
                selectedMapel: {},
                filterKelas: '{{ $selectedKelas ?? '' }}',
                filterTipe: '{{ $selectedTipe ?? '' }}',

                applyFilter() {
                    let baseUrl = '{{ route('soal.pilih_mapel', ['jenjang' => $jenjang]) }}';
                    let params = new URLSearchParams();
                    if (this.filterKelas) {
                        params.append('kelas', this.filterKelas);
                    }
                    if (this.filterTipe) {
                        params.append('tipe', this.filterTipe);
                    }
                    window.location.href = `${baseUrl}?${params.toString()}`;
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
