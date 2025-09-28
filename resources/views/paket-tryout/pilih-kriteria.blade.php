<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Paket Tryout Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100"
                     x-data="{
                         jenjang: 'SD',
                         kelas: '',
                         kelasOptions: [],
                         updateKelasOptions() {
                             if (this.jenjang === 'SD') this.kelasOptions = [1, 2, 3, 4, 5, 6];
                             else if (this.jenjang === 'SMP') this.kelasOptions = [7, 8, 9];
                             else if (this.jenjang === 'SMA') this.kelasOptions = [10, 11, 12];
                             else this.kelasOptions = [];
                             this.kelas = '';
                         },
                         submit() {
                             if (this.jenjang && this.kelas) {
                                 let baseUrl = '{{ url('/paket-tryout/create') }}';
                                 window.location.href = `${baseUrl}/${this.jenjang}/${this.kelas}`;
                             } else {
                                 alert('Harap pilih Jenjang dan Kelas terlebih dahulu.');
                             }
                         }
                     }"
                     x-init="updateKelasOptions()">

                    <h3 class="text-xl font-semibold mb-2">Pilih Kriteria Tryout</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Pilih jenjang dan kelas untuk memfilter mata pelajaran yang tersedia.</p>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="jenjang" :value="__('Jenjang Pendidikan')" />
                            <select id="jenjang" x-model="jenjang" @change="updateKelasOptions()" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="kelas" :value="__('Kelas')" />
                            <select id="kelas" x-model="kelas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="" disabled>Pilih Kelas</option>
                                <template x-for="k in kelasOptions" :key="k">
                                    <option :value="k" x-text="k"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button @click="submit()" x-bind:disabled="!jenjang || !kelas">
                            {{ __('Lanjutkan') }}
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
