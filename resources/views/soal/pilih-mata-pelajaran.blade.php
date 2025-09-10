<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pilih Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 md:p-8 text-gray-900">
                    <p class="text-gray-600">Pilih mata pelajaran yang ingin Anda kelola bank soalnya untuk jenjang **{{ $jenjang }}**.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse ($mataPelajaran as $mapel)
                <a href="{{ route('mata-pelajaran.soal.index', ['mata_pelajaran' => $mapel->id]) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="text-3xl mr-4">📝</div>
                            <div>
                                <h4 class="text-xl font-bold text-gray-800">{{ $mapel->nama_mapel }}</h4>
                                <p class="text-gray-500 text-sm">Lihat & Tambah Soal</p>
                            </div>
                        </div>
                        @if ($mapel->is_wajib)
                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">Wajib</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">Opsional</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-10 bg-white rounded-lg shadow-sm">
                    <p>Belum ada data mata pelajaran untuk jenjang ini.</p>
                </div>
            @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
