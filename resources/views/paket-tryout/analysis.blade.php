<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Analisis Soal: {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div x-data="{
        showModal: false,
        modalData: {
            question: '',
            studentsCorrect: [],
            studentsIncorrect: []
        }
    }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Respons per Butir Soal</h3>
                            <p class="mt-1 text-sm text-gray-600">Total Pengerjaan Ujian: {{ $totalResponses }} siswa</p>
                        </div>
                        <a href="{{ route('paket-tryout.responses', $paketTryout->id) }}" class="text-sm text-gray-600 hover:text-gray-900">
                            &larr; Kembali ke Ringkasan
                        </a>
                    </div>
                </div>

                @forelse ($analysisDataByMapel as $namaMapel => $analysisData)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ open: true }">
                        <div @click="open = !open" class="p-6 cursor-pointer flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">{{ $namaMapel }}</h3>
                            <svg class="w-6 h-6 transform transition-transform" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div x-show="open" x-transition class="p-6 border-t border-gray-200 space-y-4">
                            @foreach ($analysisData as $index => $data)
                                <div class="p-4 border rounded-md">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-grow pr-4">
                                            <p class="font-semibold">{!! ($index + 1) . '. ' . $data->pertanyaan !!}</p>

                                            {{-- INFORMASI JUMLAH PENJAWAB --}}
                                            <p class="text-xs text-gray-500 mt-2">
                                                Dijawab oleh: <strong class="text-gray-700">{{ $data->total_answered }} dari {{ $totalResponses }} siswa</strong>
                                            </p>
                                        </div>
                                        <button @click="showModal = true; modalData = { question: '{!! addslashes(preg_replace('/[\r\n]+/', ' ', $data->pertanyaan)) !!}', studentsCorrect: {{ json_encode($data->students_correct) }}, studentsIncorrect: {{ json_encode($data->students_incorrect) }} }" class="text-sm text-blue-600 hover:underline flex-shrink-0">
                                            Lihat Siswa
                                        </button>
                                    </div>

                                    @if($data->pilihan->isNotEmpty())
                                    <div class="mt-4 space-y-2">
                                        @foreach ($data->pilihan as $pilihan)
                                            <div class="flex items-center">
                                                <div class="w-full md:w-2/3 bg-gray-200 rounded-full h-6">
                                                    @php
                                                        // Persentase dihitung dari total siswa yang menjawab soal ini
                                                        $percentage = $data->total_answered > 0 ? ($pilihan['count'] / $data->total_answered) * 100 : 0;
                                                    @endphp
                                                    <div class="h-6 rounded-full text-white text-xs flex items-center justify-center {{ $pilihan['is_correct'] ? 'bg-green-500' : 'bg-gray-400' }}" style="width: {{ max(5, $percentage) }}%;" title="{{ $pilihan['count'] }} siswa memilih ini">
                                                        <span class="px-2">{{ number_format($percentage, 0) }}%</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4 w-full md:w-1/3 flex items-center">
                                                    <span class="text-sm">{!! $pilihan['teks'] !!}</span>
                                                    @if ($pilihan['is_correct'])
                                                        <span class="ml-2 text-green-600 text-xs">✅</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                     <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center text-gray-500">
                            Belum ada respons untuk dianalisis pada paket ini.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <div x-show="showModal" @keydown.escape.window="showModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" style="display: none;">
            <div @click.outside="showModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Detail Respons Siswa</h3>
                    <p class="text-sm text-gray-600 mt-1 line-clamp-2" x-html="modalData.question"></p>
                </div>
                <div class="p-6 overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-700 mb-2 flex items-center">
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
                        <h4 class="font-semibold text-red-700 mb-2 flex items-center">
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
                <div class="p-4 bg-gray-50 border-t rounded-b-lg text-right">
                    <button @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
