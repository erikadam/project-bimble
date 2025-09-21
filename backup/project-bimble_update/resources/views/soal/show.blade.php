<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Detail Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">

                    {{-- Informasi Soal --}}
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-100">Pertanyaan:</h3>
                        <div class="mt-2 p-4 bg-gray-900/50 border border-gray-700 rounded-md">
                            {{-- Tampilkan gambar soal jika ada --}}
                            @if ($soal->gambar_path)
                                <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="max-w-xs max-h-80 mb-4 rounded-lg shadow-sm object-contain">
                            @endif
                            <div class="prose prose-lg max-w-none text-gray-300">{!! $soal->pertanyaan !!}</div>
                        </div>
                        <div class="text-sm text-gray-400 mt-2">
                            Tipe Soal: <span class="font-semibold">{{ str_replace('_', ' ', $soal->tipe_soal) }}</span>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-700">

                    {{-- Pilihan Jawaban --}}
                    <div>
                        <h3 class="text-xl font-bold text-gray-100">Kunci Jawaban & Pilihan:</h3>
                        <div class="mt-4 space-y-3">
                            @forelse ($soal->pilihanJawaban as $pilihan)
                                <div class="flex items-start p-3 rounded-lg {{ $pilihan->apakah_benar ? 'bg-green-500/10 border border-green-400/30' : 'bg-gray-700/50 border border-gray-700' }}">
                                    <div class="flex-shrink-0 mt-1">
                                        @if ($pilihan->apakah_benar)
                                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                        @endif
                                    </div>
                                    <div class="ms-4">
                                        {{-- Tampilkan gambar pilihan jika ada --}}
                                        @if($pilihan->gambar_path)
                                            <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs max-h-60 mb-2 rounded-lg shadow-sm object-contain">
                                        @endif
                                        <div class="prose max-w-none text-gray-300">{!! $pilihan->pilihan_teks !!}</div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">Tidak ada pilihan jawaban untuk soal ini.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end mt-8 space-x-4 border-t border-gray-700 pt-6">
                        <a href="{{ route('mata-pelajaran.soal.index', $soal->mata_pelajaran_id) }}" class="text-sm text-gray-400 hover:text-gray-200 underline">
                            &larr; Kembali ke Daftar Soal
                        </a>
                        <a href="{{ route('soal.edit', $soal->id) }}" class="inline-block bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300">
                            Edit Soal
                        </a>
                        {{-- FORM UNTUK TOMBOL HAPUS --}}
                        <form method="POST" action="{{ route('soal.destroy', $soal->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini? Ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                Hapus Soal
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
