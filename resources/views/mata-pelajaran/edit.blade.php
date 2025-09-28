<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Mata Pelajaran: ') }} <span class="text-yellow-400">{{ $mataPelajaran->nama_mapel }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('mata-pelajaran.update', $mataPelajaran->id) }}"
                          x-data="formKelasData('{{ old('jenjang_pendidikan', $mataPelajaran->jenjang_pendidikan) }}', '{{ old('kelas', $mataPelajaran->kelas) }}')">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" />
                                <x-text-input id="nama_mapel" class="block mt-1 w-full" type="text" name="nama_mapel" :value="old('nama_mapel', $mataPelajaran->nama_mapel)" required autofocus />
                                <x-input-error :messages="$errors->get('nama_mapel')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jenjang_pendidikan" :value="__('Jenjang Pendidikan')" />
                                <select name="jenjang_pendidikan" id="jenjang_pendidikan" x-model="jenjang" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenjang_pendidikan')" class="mt-2" />
                            </div>

                            <div x-show="jenjang">
                                <x-input-label for="kelas" :value="__('Kelas')" />
                                <select name="kelas" id="kelas" x-model="selectedKelas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Pilih Kelas</option>
                                    <template x-for="k in kelasOptions" :key="k">
                                        <option :value="k" x-text="k"></option>
                                    </template>
                                </select>
                                <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-900 dark:text-gray-100 mb-2">Tipe</p>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="is_wajib" value="0" class="text-indigo-600 focus:ring-indigo-500" {{ old('is_wajib', $mataPelajaran->is_wajib) == 0 ? 'checked' : '' }}>
                                    <span class="ms-2">Opsional</span>
                                </label>
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="is_wajib" value="1" class="text-indigo-600 focus:ring-indigo-500" {{ old('is_wajib', $mataPelajaran->is_wajib) == 1 ? 'checked' : '' }}>
                                    <span class="ms-2">Wajib</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ url()->previous() }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formKelasData', (initialJenjang, initialKelas) => ({
                jenjang: initialJenjang,
                selectedKelas: initialKelas || '',
                kelasOptions: [],
                init() {
                    this.updateKelasOptions();
                    this.$watch('jenjang', () => this.updateKelasOptions(true));
                },
                updateKelasOptions(jenjangChanged = false) {
                    const oldSelectedKelas = this.selectedKelas;
                    if (this.jenjang === 'SD') this.kelasOptions = [1, 2, 3, 4, 5, 6];
                    else if (this.jenjang === 'SMP') this.kelasOptions = [7, 8, 9];
                    else if (this.jenjang === 'SMA') this.kelasOptions = [10, 11, 12];
                    else this.kelasOptions = [];

                    if (jenjangChanged || !this.kelasOptions.includes(parseInt(oldSelectedKelas))) {
                        this.selectedKelas = '';
                    } else {
                        this.selectedKelas = oldSelectedKelas;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
