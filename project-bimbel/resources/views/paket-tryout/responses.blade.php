<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Respons Ujian: ') }} {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div x-data="laporanData()">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold">Ringkasan</h3>
                            <div class="flex space-x-2">
                                <button type="button" @click="openBobotModal()" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Custom Bobot Nilai
                                </button>
                                <a href="{{ route('paket-tryout.analysis', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Analisis Per Soal
                                </a>
                                <a href="{{ route('paket-tryout.export_laporan', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Export ke Excel
                                </a>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
                            <div>
                                <p class="text-4xl font-bold text-indigo-600">{{ $responseCount }}</p>
                                <p class="text-sm text-gray-500 mt-1">Jumlah Respons</p>
                            </div>
                            <div>
                                <p class="text-4xl font-bold text-indigo-600">{{ number_format($averageScore, 2) }}</p>
                                <p class="text-sm text-gray-500 mt-1">Rata-rata Skor Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-xl font-bold mb-4">Detail per Siswa</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Nama Siswa</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Kelompok</th>
                                        @foreach ($semuaMapelPaket as $mapel)
                                            <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase">{{ $mapel->nama_mapel }}</th>
                                        @endforeach
                                        <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-indigo-600 uppercase">Total Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm font-medium">{{ $student->nama_lengkap }}</td>
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $student->kelompok }}</td>
                                        @foreach ($semuaMapelPaket as $mapel)
                                            <td class="px-5 py-4 border-b border-gray-200 text-sm text-center">
                                                @php
                                                    $hasil = $student->hasil_per_mapel[$mapel->id] ?? null;
                                                @endphp
                                                @if ($hasil && $hasil['dikerjakan'])
                                                    <button type="button"
                                                            @click="openDetailModal({{ json_encode($hasil) }}, '{{ addslashes($student->nama_lengkap) }}')"
                                                            class="font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                                        {{ number_format($hasil['skor'], 2) }}
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 italic text-xs">Tidak dikerjakan</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm text-center font-bold text-indigo-700">
                                            {{ number_format($student->skor_total, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ 4 + $semuaMapelPaket->count() }}" class="text-center py-10 text-gray-500">
                                            Belum ada siswa yang mengerjakan paket ujian ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                 <div class="text-center mt-4">
                    <a href="{{ route('paket-tryout.laporan_index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        &larr; Kembali ke Daftar Laporan
                    </a>
                </div>
            </div>
        </div>

        <div x-show="showDetailModal" @keydown.escape.window="showDetailModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;">
            <div @click.outside="showDetailModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold" x-text="`Detail Jawaban: ${detailModalData.studentName} - ${detailModalData.subjectName}`"></h3>
                    <div class="flex justify-between items-center text-sm mt-2">
                        <span x-text="`Total Benar: ${detailModalData.totalCorrect} / ${detailModalData.totalQuestions}`"></span>
                        <span class="font-bold text-lg" x-text="`Skor: ${Number(detailModalData.score).toFixed(2)}`"></span>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto space-y-4">
                    <template x-for="(answer, index) in detailModalData.answers" :key="index">
                        <div class="p-4 border rounded-md" :class="answer.is_correct ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50'">
                            <p class="font-semibold" x-html="`${index + 1}. ${answer.pertanyaan}`"></p>
                            <div class="mt-2 text-sm">
                                <p><strong>Jawaban Siswa:</strong> <span :class="!answer.is_correct && 'text-red-600'" x-text="answer.jawaban_peserta || 'Tidak dijawab'"></span></p>
                                <p class="text-green-700" x-show="!answer.is_correct"><strong>Kunci Jawaban:</strong> <span x-text="answer.kunci_jawaban"></span></p>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="p-4 bg-gray-50 border-t rounded-b-lg text-right">
                    <button @click="showDetailModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Tutup</button>
                </div>
            </div>
        </div>

        <div x-show="showBobotModal" @keydown.escape.window="showBobotModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.outside="showBobotModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold">Pengaturan Bobot Nilai Soal</h3>
                        <p class="text-sm text-gray-600">Total Bobot Keseluruhan: <span x-text="totalBobot" class="font-bold"></span></p>
                    </div>
                    <div x-show="loadingBobot">
                        <p class="text-sm text-gray-500">Memuat...</p>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto">
                    <div class="space-y-4">
                        <template x-for="(soals, mapel) in bobotData" :key="mapel">
                            <div class="border rounded-lg">
                                <div @click="toggleMapel(mapel)" class="p-4 cursor-pointer flex justify-between items-center hover:bg-gray-50">
                                    <div>
                                        <h4 class="font-bold text-lg" x-text="mapel"></h4>
                                        <p class="text-sm text-gray-500">Total Bobot Mapel: <span x-text="calculateMapelBobot(mapel)" class="font-semibold"></span></p>
                                    </div>
                                    <svg class="w-6 h-6 transform transition-transform" :class="{'rotate-180': openMapel === mapel}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                                <div x-show="openMapel === mapel" x-transition class="p-4 border-t">
                                    <div class="space-y-3">
                                        <template x-for="(soal, index) in soals" :key="soal.id">
                                            <div class="grid grid-cols-12 gap-4 items-center">
                                                <div class="col-span-10">
                                                    <p class="text-sm truncate" x-html="`${index + 1}. ${soal.pertanyaan}`"></p>
                                                </div>
                                                <div class="col-span-2">
                                                    <x-text-input type="number" x-model.number="soal.pivot.bobot" @input="calculateTotalBobot()" class="w-full text-center" min="0" step="0.5" />
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 border-t rounded-b-lg flex justify-end items-center space-x-2">
                    <span x-show="saveStatus.message" :class="saveStatus.success ? 'text-green-600' : 'text-red-600'" class="text-sm" x-text="saveStatus.message"></span>
                    <button @click="showBobotModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button @click="saveBobot()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" :disabled="isSaving">
                        <span x-show="!isSaving">Simpan Perubahan</span>
                        <span x-show="isSaving">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function laporanData() {
            return {
                showDetailModal: false,
                detailModalData: {
                    studentName: '',
                    subjectName: '',
                    answers: [],
                    score: 0,
                    totalCorrect: 0,
                    totalQuestions: 0
                },
                showBobotModal: false,
                bobotData: {},
                totalBobot: 0,
                loadingBobot: false,
                isSaving: false,
                saveStatus: { success: false, message: '' },
                openMapel: null, // Menyimpan mapel yang sedang terbuka

                toggleMapel(mapel) {
                    if (this.openMapel === mapel) {
                        this.openMapel = null;
                    } else {
                        this.openMapel = mapel;
                    }
                },

                openDetailModal(hasil, studentName) {
                    this.detailModalData = {
                        studentName: studentName,
                        subjectName: hasil.nama_mapel,
                        answers: hasil.detail_jawaban,
                        score: hasil.skor,
                        totalCorrect: hasil.total_benar,
                        totalQuestions: hasil.total_soal
                    };
                    this.showDetailModal = true;
                },

                openBobotModal() {
                    this.showBobotModal = true;
                    this.loadingBobot = true;
                    fetch(`{{ route('paket-tryout.get_bobot', $paketTryout->id) }}`)
                        .then(res => res.json())
                        .then(data => {
                            this.bobotData = data;
                            this.calculateTotalBobot();
                            this.loadingBobot = false;
                        });
                },

                calculateTotalBobot() {
                    let total = 0;
                    for (const mapel in this.bobotData) {
                        this.bobotData[mapel].forEach(soal => {
                            total += parseFloat(soal.pivot.bobot) || 0;
                        });
                    }
                    this.totalBobot = total;
                },

                calculateMapelBobot(mapel) {
                    if (!this.bobotData[mapel]) return 0;
                    return this.bobotData[mapel].reduce((acc, soal) => acc + (parseFloat(soal.pivot.bobot) || 0), 0);
                },

                saveBobot() {
                    this.isSaving = true;
                    this.saveStatus.message = '';

                    const payload = [];
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
