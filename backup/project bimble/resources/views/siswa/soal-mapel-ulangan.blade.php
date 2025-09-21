<x-guest-layout>
    <div class="mb-4 text-center">
        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ $ulangan->nama_ulangan }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ulangan->mataPelajaran->nama_mapel }}</p>
    </div>

    <form id="ulangan-form" action="{{ route('siswa.ulangan.simpan', $ulangan->id) }}" method="POST">
        @csrf
        @foreach ($ulangan->soal as $index => $soal)
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                {{-- Pertanyaan --}}
                <div class="flex items-start mb-3">
                    <span class="font-bold mr-2 text-gray-800 dark:text-gray-200">{{ $index + 1 }}.</span>
                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">

                        {!! $soal->pertanyaan !!}
                                                @if ($soal->gambar_path)
                            <div class="mb-4">
                                <img class="img-fluid" src="{{ asset('storage/' . $soal->gambar_path) }}" alt="Gambar Soal" style="max-width: 100%; height: auto;">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pilihan Jawaban --}}
                <div class="space-y-3 pl-6">
                    @foreach ($soal->pilihanJawaban as $pilihan)
                        <label for="pilihan-{{ $pilihan->id }}" class="flex items-center p-3 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer">
                            <input class="rounded-full border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 dark:bg-gray-900"
                                   type="radio"
                                   name="jawaban_soal[{{ $soal->id }}]"
                                   id="pilihan-{{ $pilihan->id }}"
                                   value="{{ $pilihan->id }}">
                            <span class="ml-3 text-sm text-gray-600 dark:text-gray-300">
                                {!! $pilihan->pilihan_teks !!}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-center mt-6">
            <x-primary-button>
                {{ __('Selesaikan Ulangan') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
