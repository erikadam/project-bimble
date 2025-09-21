<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pratinjau Paket: {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">Daftar Soal</h3>

                    @forelse ($paketTryout->mataPelajaran as $mapel)
                        @if ($mapel->soal->count() > 0)
                            <div class="mb-8 p-6 bg-gray-50 rounded-lg border">
                                <h4 class="text-xl font-bold text-gray-800 mb-4">{{ $mapel->nama_mapel }} ({{ $mapel->soal->count() }} soal)</h4>

                                <div class="space-y-6">
                                    @foreach ($mapel->soal as $soal)
                                        <div class="p-4 bg-white rounded-md shadow-sm border border-gray-200">
                                            <p class="font-medium text-lg mb-2">{{ $loop->iteration }}. {{ $soal->pertanyaan }}</p>

                                            {{-- Tipe Pilihan Ganda --}}
                                            @if ($soal->tipe_soal === 'pilihan_ganda')
                                                <div class="space-y-2">
                                                    @foreach ($soal->pilihanJawaban as $pilihan)
                                                        <div class="p-2 border rounded-md {{ $pilihan->apakah_benar ? 'bg-green-100 border-green-400' : 'bg-gray-50' }}">
                                                            <p class="text-sm">
                                                                {{ $pilihan->apakah_benar ? '✅ ' : ' ' }}{{ $pilihan->pilihan_teks }}
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            {{-- Tipe Isian --}}
                                            @elseif ($soal->tipe_soal === 'isian')
                                                <div class="p-2 border rounded-md bg-green-100 border-green-400">
                                                    <p class="text-sm font-semibold">Jawaban Benar:</p>
                                                    <p class="text-sm">{{ $soal->pilihanJawaban->first()->pilihan_teks }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-center text-gray-500 py-10">Paket ini tidak memiliki mata pelajaran dengan soal yang aktif.</p>
                    @endforelse

                    <div class="flex items-center justify-center mt-8">
                        <a href="{{ route('paket-tryout.index') }}" class="inline-block text-gray-600 hover:text-gray-900 font-medium">
                            &larr; Kembali ke Daftar Paket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
