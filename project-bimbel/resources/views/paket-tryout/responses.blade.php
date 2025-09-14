<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Respons Ujian: ') }} {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    {{-- Inisialisasi data Alpine.js --}}
    <div x-data="laporanData()">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                {{-- Bagian Ringkasan dan Tombol Aksi --}}
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-100">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <h3 class="text-xl font-bold text-gray-100">Ringkasan</h3>
                            <div class="flex flex-wrap space-x-2">
                                {{-- Tombol Custom Bobot Nilai --}}
                                <button type="button" @click="openBobotModal()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Custom Bobot Nilai
                                </button>
                                {{-- Tombol Analisis Per Soal --}}
                                <a href="{{ route('paket-tryout.analysis', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Analisis Per Soal
                                </a>
                                {{-- Tombol Export ke Excel --}}
                                <a href="{{ route('paket-tryout.export_laporan', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Export ke Excel
                                </a>
                            </div>
                        </div>

                        {{-- Kartu Statistik --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-300">Total Pengerjaan</h4>
                                <p class="text-2xl font-bold text-white">{{ $responseCount }} Siswa</p>
                            </div>
                            <div class="bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-300">Rata-rata Skor Total</h4>
                                <p class="text-2xl font-bold text-white">{{ number_format($averageScore, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Detail Respons per Siswa --}}
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-100 mb-4">Detail Respons per Siswa</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-700 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-700 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Skor Total</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-700 bg-gray-700 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $student)
                                    <tr class="hover:bg-gray-700">
                                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                            <p class="text-gray-200 whitespace-no-wrap">{{ $student->nama_lengkap }}</p>
                                            <p class="text-gray-400 text-xs whitespace-no-wrap">{{ $student->kelompok }}</p>
                                        </td>
                                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                            <p class="font-bold text-lg {{ $student->skor_total >= 70 ? 'text-green-400' : 'text-red-400' }}">{{ $student->skor_total }}</p>
                                        </td>
                                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                                            {{-- Tombol untuk membuka modal detail jawaban siswa --}}
                                            <button @click="selectedStudent = {{ $student->id }}; openDetailModal()" class="text-yellow-400 hover:text-yellow-300">Lihat Detail</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-10 text-gray-500">
                                            Belum ada siswa yang mengerjakan paket ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal untuk Detail Jawaban Siswa --}}
        <div x-show="detailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
            <div @click.outside="closeDetailModal()" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="p-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-100">Detail Jawaban: <span x-text="getStudentById(selectedStudent)?.nama_lengkap"></span></h3>
                </div>
                <div class="p-6 overflow-y-auto space-y-4">
                    <template x-if="getStudentById(selectedStudent)">
                        <div class="space-y-3">
                            <template x-for="mapel in Object.values(getStudentById(selectedStudent).hasil_per_mapel)" :key="mapel.nama_mapel">
                                <div class="bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <p class="font-bold text-gray-200" x-text="mapel.nama_mapel"></p>
                                        <p class="text-sm font-semibold" :class="mapel.dikerjakan ? (mapel.skor >= 70 ? 'text-green-400' : 'text-red-400') : 'text-gray-500'" x-text="mapel.dikerjakan ? `Skor: ${mapel.skor}` : 'Tidak Dikerjakan'"></p>
                                    </div>
                                    <template x-if="mapel.dikerjakan">
                                        <div class="mt-2 text-sm text-gray-300">
                                            <span>Benar: <span x-text="mapel.total_benar"></span>/<span x-text="mapel.total_soal"></span> Soal</span>
                                            <div class="mt-4 space-y-4">
                                                <template x-for="(jawaban, index) in mapel.detail_jawaban" :key="index">
                                                    <div class="border-t border-gray-600 pt-3">
                                                         <div class="flex justify-between items-start">
                                                             <div class="prose prose-sm max-w-none text-gray-300" x-html="jawaban.pertanyaan"></div>
                                                             <span class="ml-4 text-xs font-mono px-2 py-1 rounded" :class="jawaban.is_correct ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300'" x-text="jawaban.is_correct ? 'Benar' : 'Salah'"></span>
                                                         </div>
                                                         <div class="mt-2 text-xs">
                                                             <p>Bobot: <span class="font-semibold" x-text="jawaban.bobot"></span></p>
                                                             <p>Jawabanmu: <span class="font-semibold" x-html="jawaban.jawaban_peserta || '[Tidak Dijawab]'"></span></p>
                                                             <p>Kunci: <span class="font-semibold" x-text="jawaban.kunci_jawaban"></span></p>
                                                         </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <div class="p-4 bg-gray-900 border-t border-gray-700 text-right">
                    <button @click="closeDetailModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-500">Tutup</button>
                </div>
            </div>
        </div>

        {{-- Modal untuk Custom Bobot Nilai --}}
        <div x-show="bobotModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" style="display: none;">
            <div @click.outside="closeBobotModal()" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col">
                <div class="p-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-100">Custom Bobot Nilai</h3>
                </div>
                <div class="p-6 overflow-y-auto space-y-4">
                    <template x-if="isLoading">
                        <p class="text-center text-gray-400">Memuat data soal...</p>
                    </template>
                    <template x-if="!isLoading && Object.keys(bobotData).length > 0">
                        <div class="space-y-6">
                           <template x-for="(soalGroup, mapel) in bobotData" :key="mapel">
                                <div class="bg-gray-700 p-4 rounded-lg">
                                    <h4 class="font-bold text-gray-200 mb-3" x-text="mapel"></h4>
                                    <div class="space-y-3">
                                        <template x-for="soal in soalGroup" :key="soal.id">
                                            <div class="flex items-center justify-between">
                                                <div class="prose prose-sm max-w-none text-gray-300" x-html="soal.pertanyaan.substring(0, 100) + '...'"></div>
                                                <input type="number" step="0.1" min="0" class="w-24 ml-4 bg-gray-800 border-gray-600 text-gray-200 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500" x-model.number="soal.pivot.bobot">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                           </template>
                        </div>
                    </template>
                </div>
                <div class="p-4 bg-gray-900 border-t border-gray-700 flex justify-between items-center">
                    <span x-show="saveStatus.message" :class="saveStatus.success ? 'text-green-400' : 'text-red-400'" x-text="saveStatus.message" class="text-sm"></span>
                    <div>
                        <button @click="closeBobotModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-500 mr-2">Batal</button>
                        <button @click="saveBobot()" :disabled="isSaving" class="px-4 py-2 bg-yellow-500 text-gray-900 rounded-md hover:bg-yellow-400 disabled:opacity-50">
                            <span x-show="!isSaving">Simpan Perubahan</span>
                            <span x-show="isSaving">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Alpine.js --}}
    <script>
        function laporanData() {
            return {
                students: @json($students),
                selectedStudent: null,
                detailModalOpen: false,
                bobotModalOpen: false,
                isLoading: false,
                isSaving: false,
                bobotData: {},
                saveStatus: { success: false, message: '' },

                getStudentById(id) {
                    return this.students.find(s => s.id === id);
                },

                openDetailModal() {
                    this.detailModalOpen = true;
                },
                closeDetailModal() {
                    this.detailModalOpen = false;
                    this.selectedStudent = null;
                },

                openBobotModal() {
                    this.bobotModalOpen = true;
                    this.loadBobotData();
                },
                closeBobotModal() {
                    this.bobotModalOpen = false;
                },

                loadBobotData() {
                    this.isLoading = true;
                    fetch(`{{ route('paket-tryout.get_bobot', $paketTryout->id) }}`)
                        .then(res => res.json())
                        .then(data => {
                            this.bobotData = data;
                        })
                        .catch(err => console.error(err))
                        .finally(() => this.isLoading = false);
                },

                saveBobot() {
                    this.isSaving = true;
                    let payload = [];
                    for (const mapel in this.bobotData) {
                        this.bobotData[mapel].forEach(soal => {
                            payload.push({
                                soal_id: soal.id,
                                bobot: soal.pivot.bobot
                            });
                        });
                    }

                    fetch(`{{ route('paket-tryout.save_bobot', $paketTryout->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ bobots: payload })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.saveStatus = { success: true, message: data.message };
                        setTimeout(() => window.location.reload(), 1500); // Reload halaman untuk melihat skor terbaru
                    })
                    .catch(() => {
                        this.saveStatus = { success: false, message: 'Terjadi kesalahan.' };
                    })
                    .finally(() => {
                        this.isSaving = false;
                        setTimeout(() => this.saveStatus.message = '', 3000);
                    });
                }
            }
        }
    </script>
</x-app-layout>
