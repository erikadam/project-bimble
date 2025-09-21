<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('mata-pelajaran.update', $mataPelajaran->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        {{-- Input hidden untuk jenjang pendidikan --}}
                        <input type="hidden" name="jenjang_pendidikan" value="{{ old('jenjang_pendidikan', $mataPelajaran->jenjang_pendidikan) }}">

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" />
                                <x-text-input id="nama_mapel" name="nama_mapel" type="text" class="mt-1 block w-full" :value="old('nama_mapel', $mataPelajaran->nama_mapel)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_mapel')" />
                            </div>

                            <div class="flex items-center space-x-4">
                                <p class="text-sm text-gray-700">Tipe:</p>
                                <label class="flex items-center">
                                    <input type="radio" name="is_wajib" value="0" @checked(old('is_wajib', $mataPelajaran->is_wajib) == 0) class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">Opsional</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_wajib" value="1" @checked(old('is_wajib', $mataPelajaran->is_wajib) == 1) class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">Wajib</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                            <a href="{{ route('mata-pelajaran.index', ['jenjang' => $mataPelajaran->jenjang_pendidikan]) }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
