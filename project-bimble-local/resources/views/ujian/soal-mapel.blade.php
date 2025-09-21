<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ujian') }}: {{ $mapel->nama_mapel }}
            </h2>
            <div id="timer" class="text-xl font-bold text-red-600"></div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="ujian-form" method="POST" action="{{ route('demo.ujian.simpan_jawaban', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapel->id]) }}">
                        @csrf

                        <div class="space-y-8">
                            @forelse ($mapel->soal as $soal)
                                <div class="p-4 border rounded-lg shadow-sm">
                                    {{-- Tampilkan gambar soal jika ada --}}
                                    @if ($soal->gambar_path)
                                        <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="max-w-md max-h-80 mb-4 rounded-lg shadow-sm object-contain">
                                    @endif

                                    <div class="font-bold text-lg mb-4">{!! $loop->iteration . '. ' . $soal->pertanyaan !!}</div>

                                    @if ($soal->tipe_soal === 'pilihan_ganda')
                                        <div class="space-y-2">
                                            @foreach ($soal->pilihanJawaban as $pilihan)
                                                <label class="flex items-start space-x-2 p-2 border rounded-md cursor-pointer hover:bg-gray-50">
                                                    <input type="radio" name="jawaban_soal[{{ $soal->id }}]" value="{{ $pilihan->pilihan_teks }}" class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                                    <div>
                                                        @if ($pilihan->gambar_path)
                                                            <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs max-h-40 mb-2 rounded-lg shadow-sm object-contain">
                                                        @endif
                                                        <span>{!! $pilihan->pilihan_teks !!}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif ($soal->tipe_soal === 'pilihan_ganda_majemuk')
                                        <div class="space-y-2">
                                            @foreach ($soal->pilihanJawaban as $pilihan)
                                                <label class="flex items-start space-x-2 p-2 border rounded-md cursor-pointer hover:bg-gray-50">
                                                    <input type="checkbox" name="jawaban_soal[{{ $soal->id }}][]" value="{{ $pilihan->pilihan_teks }}" class="mt-1 text-indigo-600 focus:ring-indigo-500 rounded">
                                                    <div>
                                                        @if ($pilihan->gambar_path)
                                                            <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs max-h-40 mb-2 rounded-lg shadow-sm object-contain">
                                                        @endif
                                                        <span>{!! $pilihan->pilihan_teks !!}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif ($soal->tipe_soal === 'isian')
                                        <x-text-input type="text" name="jawaban_soal[{{ $soal->id }}]" class="w-full mt-2" placeholder="Jawaban isian" />
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Tidak ada soal untuk mata pelajaran ini.</p>
                            @endforelse
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-primary-button>
                                {{ $mapelSelanjutnyaId ? __('Lanjut ke Mata Pelajaran Selanjutnya') : __('Selesai Ujian') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('ujian-form');
            const timerDisplay = document.getElementById('timer');

            let waktuSisa = {{ $sisaWaktu }};

            const countdown = setInterval(function() {
                const minutes = Math.floor(waktuSisa / 60);
                const seconds = waktuSisa % 60;

                timerDisplay.textContent = `Waktu: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (waktuSisa <= 0) {
                    clearInterval(countdown);
                    timerDisplay.textContent = 'Waktu Habis!';
                    form.submit();
                }
                waktuSisa--;
            }, 1000);
        });
    </script>
</x-app-layout>
