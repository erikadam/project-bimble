<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bank Soal: {{ $mataPelajaran->nama_mapel }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 text-right">
                <a href="{{ route('mata-pelajaran.soal.create', ['mata_pelajaran' => $mataPelajaran->id]) }}" class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                    + Tambah Soal Baru
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold mb-4">Daftar Soal</h3>

                    <div class="space-y-6">
                        @forelse ($soalItems as $soal)
                            <div class="p-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col md:flex-row md:items-center justify-between">
                                <div class="flex-grow">
                                    <div class="flex items-start space-x-3 mb-2">
                                        <p class="font-bold text-lg leading-tight">{{ $loop->iteration }}.</p>
                                        <div>
                                            {{-- Tampilkan gambar soal jika ada --}}
                                            @if ($soal->gambar_path)
                                                <img src="{{ Storage::url($soal->gambar_path) }}" alt="Gambar Soal" class="max-w-xs max-h-40 mb-2 rounded-lg object-contain">
                                            @endif
                                            <div class="text-lg">{!! $soal->pertanyaan !!}</div>

                                        </div>
                                    </div>
                                    <div class="md:ms-6 mt-2 md:mt-0 flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="px-2 py-1 bg-gray-100 rounded-full">{{ str_replace('_', ' ', $soal->tipe_soal) }}</span>
                                        @if ($soal->status == 'aktif')
                                            <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Nonaktif</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="md:ms-4 mt-4 md:mt-0 flex-shrink-0">
                                    <a href="{{ route('soal.show', $soal->id) }}" class="inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-10">Belum ada soal untuk mata pelajaran ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
