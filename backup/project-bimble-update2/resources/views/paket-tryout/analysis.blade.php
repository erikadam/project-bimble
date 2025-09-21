<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Analisis Soal: {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div x-data="{
        showModal: false,
        modalData: {
            question: '',
            studentsCorrect: [],
            studentsIncorrect: []
        },
        openModal(analysis) {
            this.modalData.question = analysis.pertanyaan;
            this.modalData.studentsCorrect = analysis.students_correct;
            this.modalData.studentsIncorrect = analysis.students_incorrect;
            this.showModal = true;
        }
    }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-100">Respons per Butir Soal</h3>
                            <p class="mt-1 text-sm text-gray-400">Total Pengerjaan Ujian: {{ $totalResponses }} siswa</p>
                        </div>
                        <a href="{{ route('paket-tryout.responses', $paketTryout->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            &larr; Kembali ke Ringkasan
                        </a>
                    </div>
                </div>

                @forelse ($analysisDataByMapel as $namaMapel => $analysisData)
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" x-data="{ open: true }">
                        <button @click="open = !open" class="w-full text-left px-6 py-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-100">{{ $namaMapel }}</h3>
                                <svg :class="{'transform rotate-180': open}" class="w-5 h-5 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>

                        <div x-show="open" x-collapse class="border-t border-gray-700">
                            <div class="p-6 text-gray-200 space-y-8">
                                @foreach ($analysisData as $index => $analysis)
                                    <div class="border-b border-gray-700 pb-8 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-400">Soal #{{ $index + 1 }}</p>
                                                <div class="prose prose-sm max-w-none text-gray-300 mt-2">
                                                    {!! $analysis->pertanyaan !!}
                                                </div>
                                            </div>
                                            <button @click="openModal({{ json_encode($analysis) }})" class="ml-4 text-sm text-yellow-400 hover:text-yellow-300 flex-shrink-0">
                                                Lihat Pengerja
                                            </button>
                                        </div>

                                        <div class="mt-4">
                                            <p class="text-sm text-gray-400 mb-2">
                                                <span class="font-bold">{{ $analysis->total_answered }} dari {{ $totalResponses }}</span> siswa menjawab pertanyaan ini.
                                            </p>
                                            @if ($analysis->tipe_soal === 'pilihan_ganda_kompleks')
                                                <div class="overflow-x-auto p-1 bg-gray-900/50 rounded-lg">
                                                    <table class="min-w-full text-sm">
                                                        <thead>
                                                            <tr>
                                                                <th class="p-2 text-left font-medium text-gray-400">Pernyataan</th>
                                                                @foreach ($analysis->kolom as $kolomTeks)
                                                                    <th class="p-2 text-center font-medium text-gray-400">{{ $kolomTeks }}</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-700">
                                                            @foreach ($analysis->pernyataans as $pernyataan)
                                                                <tr class="pernyataan-row">
                                                                    <td class="p-2 align-top text-gray-300">{!! $pernyataan['pernyataan_teks'] !!}</td>
                                                                    @foreach ($pernyataan['jawaban'] as $jawaban)
                                                                        <td class="p-2 text-center align-middle">
                                                                            @if ($jawaban['is_correct'])
                                                                                <span class="text-green-500 font-bold">✔</span>
                                                                            @else
                                                                                <span class="text-red-500">❌</span>
                                                                            @endif
                                                                            <span class="text-gray-400 text-xs block mt-1">({{ number_format($jawaban['percentage'], 1) }}%)</span>
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @elseif ($analysis->pilihan->isNotEmpty())
                                                <div class="space-y-2">
                                                    @foreach ($analysis->pilihan as $pilihan)
                                                        @php
                                                            // Logika persentase diubah untuk mencegah pembagian dengan nol jika tidak ada yang menjawab
                                                            $percentage = $analysis->total_answered > 0 ? ($pilihan['count'] / $analysis->total_answered) * 100 : 0;
                                                        @endphp
                                                        <div>
                                                            <div class="flex justify-between items-center text-sm mb-1">
                                                                <span class="flex items-center">
                                                                    <span class="text-gray-300">{!! $pilihan['teks'] !!}</span>

                                                                    @if ($pilihan['is_correct'])
                                                                        <svg class="w-4 h-4 text-green-400 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                                    @endif
                                                                </span>
                                                                <span class="text-gray-400">{{ $pilihan['count'] }} ({{ number_format($percentage, 1) }}%)</span>
                                                            </div>
                                                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                                                <div class="{{ $pilihan['is_correct'] ? 'bg-green-500' : 'bg-yellow-500' }} h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 bg-gray-800 shadow sm:rounded-lg">
                        <p class="text-center text-gray-400">Tidak ada data analisis yang tersedia untuk paket ini.</p>
                    </div>
                @endforelse

            </div>
        </div>

        <div x-show="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            @keydown.escape.window="showModal = false"
            style="display: none;">
            <div @click.outside="showModal = false" class="bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <div class="p-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-100">Detail Pengerja Soal</h3>
                    <div class="prose prose-sm max-w-none text-gray-300 mt-2">
                        <div x-html="modalData.question"></div>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 overflow-y-auto text-gray-300">
                    <div>
                        <h4 class="font-semibold text-green-400 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span x-text="`Jawaban Benar (${modalData.studentsCorrect.length})`"></span>
                        </h4>
                        <ul class="list-disc list-inside text-sm space-y-1 pl-2">
                            <template x-if="modalData.studentsCorrect.length === 0">
                                <li class="text-gray-500">Tidak ada.</li>
                            </template>
                            <template x-for="student in modalData.studentsCorrect" :key="student">
                                <li x-text="student"></li>
                            </template>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-red-500 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span x-text="`Jawaban Salah (${modalData.studentsIncorrect.length})`"></span>
                        </h4>
                        <ul class="list-disc list-inside text-sm space-y-1 pl-2">
                             <template x-if="modalData.studentsIncorrect.length === 0">
                                <li class="text-gray-500">Tidak ada.</li>
                            </template>
                            <template x-for="student in modalData.studentsIncorrect" :key="student">
                                <li x-text="student"></li>
                            </template>
                        </ul>
                    </div>
                </div>
                <div class="p-4 bg-gray-900 border-t border-gray-700 rounded-b-lg text-right">
                    <button @click="showModal = false" class="px-4 py-2 bg-gray-700 text-gray-200 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-yellow-500">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
