<x-guest-layout>
    <div class="py-12 bg-gray-900 min-h-screen" x-data="pilihJenjangModal()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-16 text-center">
                <a href="{{ route('welcome') }}" class="text-gray-400 hover:text-yellow-400 transition-colors duration-300 inline-flex items-center group">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Halaman Utama
                </a>
            </div>
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl">
                    Pilih Jenis Tryout
                </h1>
                <p class="mt-4 text-xl text-gray-400">
                    Silakan pilih jenis tryout yang ingin kamu ikuti.
                </p>
            </div>

            {{-- Tiga Pilihan Utama --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Card Tryout Event --}}
                <div @click="openModal('event')" class="bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center text-center hover:bg-gray-700 transform hover:-translate-y-2 transition-transform duration-300 cursor-pointer">
                    <div class="bg-red-500 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Tryout Event</h3>
                    <p class="text-gray-400">Ujian terjadwal dengan waktu mulai dan selesai yang ditentukan. Kerjakan bersama peserta lain!</p>
                </div>

                {{-- Card Tryout Fleksibel --}}
                <div @click="openModal('fleksibel')" class="bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center text-center hover:bg-gray-700 transform hover:-translate-y-2 transition-transform duration-300 cursor-pointer">
                     <div class="bg-blue-500 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Tryout Fleksibel</h3>
                    <p class="text-gray-400">Pilih mata pelajaran yang kamu suka dan kerjakan kapan saja tanpa batasan waktu.</p>
                </div>

                {{-- Card Tryout Pacu --}}
                <div @click="openModal('pacu')" class="bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center text-center hover:bg-gray-700 transform hover:-translate-y-2 transition-transform duration-300 cursor-pointer">
                     <div class="bg-green-500 p-4 rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Tryout Pacu</h3>
                    <p class="text-gray-400">Ujian berkecepatan tinggi dengan waktu pengerjaan per-mapel yang dihitung secara berurutan.</p>
                </div>

            </div>


        </div>

        {{-- Modal untuk Pilih Jenjang --}}
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
             style="display: none;">

            <div @click.outside="showModal = false"
                 x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-gray-800 rounded-xl shadow-lg w-full max-w-2xl p-8 space-y-6">

                <h2 class="text-3xl font-bold text-center text-white">Pilih Jenjang Pendidikan</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <a :href="generateUrl('SD')" class="block p-6 text-center bg-gray-700 rounded-lg hover:bg-yellow-500 hover:text-gray-900 transition-colors duration-200">
                        <span class="text-2xl font-bold text-white">SD</span>
                    </a>
                    <a :href="generateUrl('SMP')" class="block p-6 text-center bg-gray-700 rounded-lg hover:bg-yellow-500 hover:text-gray-900 transition-colors duration-200">
                        <span class="text-2xl font-bold text-white">SMP</span>
                    </a>
                    <a :href="generateUrl('SMA')" class="block p-6 text-center bg-gray-700 rounded-lg hover:bg-yellow-500 hover:text-gray-900 transition-colors duration-200">
                        <span class="text-2xl font-bold text-white">SMA</span>
                    </a>
                </div>

                 <div class="text-center">
                    <button @click="showModal = false" class="text-gray-400 hover:text-white transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pilihJenjangModal() {
            return {
                showModal: false,
                selectedType: '',
                // Definisikan base URL untuk setiap tipe tryout
                routeMap: {
                    'event': "{{ route('siswa.pilih_event', ['jenjang' => '']) }}",
                    'fleksibel': "{{ route('siswa.pilih_paket', ['jenjang' => '']) }}",
                    'pacu': "{{ route('siswa.pilih_pacu', ['jenjang' => '']) }}"
                },
                openModal(type) {
                    this.selectedType = type;
                    this.showModal = true;
                },
                generateUrl(jenjang) {
                    // Cek apakah tipe yang dipilih ada di map
                    if (this.routeMap[this.selectedType]) {
                        // Gabungkan base URL dengan jenjang yang dipilih
                        return this.routeMap[this.selectedType] + '/' + jenjang;
                    }
                    // Fallback jika terjadi error
                    return '#';
                }
            }
        }
    </script>
</x-guest-layout>
