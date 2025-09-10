<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pilih Jenjang Pendidikan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-gray-600 mb-6">Pilih jenjang pendidikan yang ingin Anda kelola mata pelajarannya.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('soal.pilih_mapel', ['jenjang' => 'SD']) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center space-x-4">
                                <div class="text-3xl">👧</div>
                                <h4 class="text-xl font-bold text-gray-800">Sekolah Dasar (SD)</h4>
                            </div>
                        </a>
                        <a href="{{ route('soal.pilih_mapel', ['jenjang' => 'SMP']) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center space-x-4">
                                <div class="text-3xl">🧑‍🎓</div>
                                <h4 class="text-xl font-bold text-gray-800">Sekolah Menengah Pertama (SMP)</h4>
                            </div>
                        </a>
                        <a href="{{ route('soal.pilih_mapel', ['jenjang' => 'SMA']) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex items-center space-x-4">
                                <div class="text-3xl">👨‍🎓</div>
                                <h4 class="text-xl font-bold text-gray-800">Sekolah Menengah Atas (SMA)</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
