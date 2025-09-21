<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Detail Soal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm text-gray-400">Mata Pelajaran: {{ $soal->mataPelajaran->nama_mapel }}</p>
                            <p class="text-sm text-gray-400">Tipe Soal: <span class="font-bold">{{ Str::title(str_replace('_', ' ', $soal->tipe_soal)) }}</span></p>
                            <p class="text-sm text-gray-400">Status: <span class="font-bold">{{ Str::title($soal->status) }}</span></p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('soal.edit', $soal->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                            <form action="{{ route('soal.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-700">

                    <div class="prose prose-invert max-w-none mb-4">
                        {!! $soal->pertanyaan !!}
                    </div>

                    @if ($soal->gambar_path)
                        <div class="mb-6">
                            <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="rounded-lg max-h-80 mx-auto object-contain">
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-gray-300 mb-4">Opsi Jawaban</h3>

                    @if ($soal->tipe_soal === 'pilihan_ganda_kompleks')
                        <div class="overflow-x-auto p-1 bg-gray-900/50 rounded-lg">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="p-2 text-left font-medium text-gray-400">Pernyataan</th>
                                        @foreach ($soal->pernyataans->first()->pilihanJawabans ?? [] as $pilihan)
                                            <th class="p-2 text-center font-medium text-gray-400">
                                                {{ $pilihan->pilihan_teks }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach ($soal->pernyataans as $pernyataan)
                                        <tr class="pernyataan-row">
                                            <td class="p-2 align-top text-gray-300">{!! $pernyataan->pernyataan_teks !!}</td>
                                            @foreach ($pernyataan->pilihanJawabans as $pilihan)
                                                <td class="p-2 text-center align-middle">
                                                    @if ($pilihan->apakah_benar)
                                                        <span class="text-green-500 font-bold">✔</span>
                                                    @else
                                                        <span class="text-red-500">❌</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <ul class="space-y-4">
                            @foreach ($soal->pilihanJawaban as $pilihan)
                                <li class="p-4 bg-gray-900 rounded-lg shadow-sm {{ $pilihan->apakah_benar ? 'border-l-4 border-green-500' : '' }}">
                                    <div class="flex items-start">
                                        <span class="mr-3 text-lg font-bold">{{ chr(65 + $loop->index) }}.</span>
                                        <div class="flex-1">
                                            <div class="prose prose-invert text-gray-300 max-w-none">{!! $pilihan->pilihan_teks !!}</div>
                                            @if($pilihan->gambar_path)
                                                <img src="{{ Storage::url($pilihan->gambar_path) }}" alt="Gambar Jawaban" class="mt-2 max-h-24 rounded-md">
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
