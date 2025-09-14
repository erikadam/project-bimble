<x-guest-layout>
    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-200">
                    <h2 class="font-semibold text-2xl text-white leading-tight mb-2">
                        Review Ulangan: {{ $ulanganSession->ulangan->nama_ulangan }}
                    </h2>
                    <p class="text-gray-400 mb-6">Berikut adalah ulasan dari jawaban yang telah Anda kirim.</p>
                    <hr class="my-6 border-gray-700">

                    <div class="space-y-8">
                        {{-- Loop untuk setiap soal dalam ulangan --}}
                        @foreach ($ulanganSession->ulangan->soal as $index => $soal)
                            <div class="p-4 bg-gray-700 rounded-lg">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center font-bold text-gray-900">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-grow">
                                        {{-- Tampilkan Pertanyaan --}}
                                        <div class="prose prose-invert max-w-none text-white mb-4">
                                            {!! $soal->pertanyaan !!}
                                        </div>

                                        {{-- Ambil jawaban siswa untuk soal ini --}}
                                        @php
                                            $jawabanDiberikan = $jawabanSiswa->get($soal->id);
                                            $idPilihanSiswa = $jawabanDiberikan ? $jawabanDiberikan->pilihan_jawaban_id : null;
                                        @endphp

                                        {{-- Loop untuk setiap pilihan jawaban --}}
                                        <div class="space-y-2">
                                            @foreach ($soal->pilihanJawaban as $pilihan)
                                                @php
                                                    $isJawabanBenar = $pilihan->apakah_benar;
                                                    $isJawabanSiswa = $pilihan->id == $idPilihanSiswa;

                                                    // Tentukan warna background berdasarkan kondisi
                                                    $bgColor = 'bg-gray-800'; // Default
                                                    if ($isJawabanBenar) {
                                                        $bgColor = 'bg-green-800 border-green-500'; // Jawaban Benar
                                                    } elseif ($isJawabanSiswa && !$isJawabanBenar) {
                                                        $bgColor = 'bg-red-800 border-red-500'; // Jawaban Siswa (Salah)
                                                    }
                                                @endphp

                                                <div class="flex items-center p-3 rounded-lg border {{ $bgColor }}">
                                                    {{-- Tampilkan ikon berdasarkan kondisi --}}
                                                    <div class="w-6 mr-3 text-center">
                                                        @if ($isJawabanBenar)
                                                            <span class="text-green-400">✔</span> {{-- Centang untuk jawaban benar --}}
                                                        @elseif ($isJawabanSiswa && !$isJawabanBenar)
                                                            <span class="text-red-400">✖</span> {{-- Silang untuk jawaban salah --}}
                                                        @endif
                                                    </div>
                                                    <span class="text-white">{!! $pilihan->pilihan_teks !!}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                     <div class="mt-8 text-center">
                        <a href="{{ route('siswa.ulangan.hasil', $ulanganSession->id) }}" class="inline-block px-6 py-3 text-sm font-semibold text-center text-white uppercase transition-all duration-200 bg-yellow-500 rounded-lg hover:bg-yellow-600">
                            Kembali ke Hasil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
