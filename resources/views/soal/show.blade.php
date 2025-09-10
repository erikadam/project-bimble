<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Soal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    {{-- Informasi Soal --}}
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Pertanyaan:</h3>
                        <div class="mt-2 p-4 bg-gray-50 rounded-md">
                            {{-- Tampilkan gambar soal jika ada --}}
                            @if ($soal->gambar_path)
                                <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="max-w-xs max-h-80 mb-4 rounded-lg shadow-sm object-contain">
                            @endif
                            <div class="text-lg">{!! $soal->pertanyaan !!}</div>
                        </div>
                        <div class="text-sm text-gray-500 mt-2">
                            Tipe Soal: <span class="font-semibold">{{ str_replace('_', ' ', $soal->tipe_soal) }}</span>
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- Pilihan Jawaban --}}
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Opsi Jawaban:</h3>
                        <div class="space-y-3">
                            @forelse ($soal->pilihanJawaban as $pilihan)
                                <div class="p-3 border rounded-md {{ $pilihan->apakah_benar ? 'bg-green-100 border-green-400' : 'bg-gray-50' }}">
                                    <div class="flex items-start space-x-3">
                                        <p>
                                            {{ $pilihan->apakah_benar ? '✅' : ' ' }}
                                        </p>
                                        <div>
                                            {{-- Tampilkan gambar pilihan jika ada --}}
                                            @if ($pilihan->gambar_path)
                                                <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs max-h-40 rounded-lg shadow-sm mb-2 object-contain">
                                            @endif
                                            <div class="text-gray-800">{!! $pilihan->pilihan_teks !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">Tidak ada pilihan jawaban untuk soal ini.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end mt-8 space-x-4">
                        <a href="{{ route('mata-pelajaran.soal.index', $soal->mata_pelajaran_id) }}" class="text-sm text-gray-600 hover:text-gray-900">
                            &larr; Kembali ke Daftar Soal
                        </a>
                        <a href="{{ route('soal.edit', $soal->id) }}" class="inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                            Edit Soal
                        </a>
                        {{-- FORM UNTUK TOMBOL HAPUS --}}
                        <form method="POST" action="{{ route('soal.destroy', $soal->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini? Ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-block bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-lg">
                                Hapus Soal
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
