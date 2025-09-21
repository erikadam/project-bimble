{{-- resources/views/ulangan/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Ulangan Baru untuk Jenjang') }} <span class="text-blue-400">{{ $jenjang }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('ulangan.store') }}" method="POST">
                        @csrf
                        {{-- Hidden input untuk membawa data jenjang --}}
                        <input type="hidden" name="jenjang" value="{{ $jenjang }}">

                        <div class="space-y-6">
                            {{-- Nama Ulangan --}}
                            <div>
                                <x-input-label for="nama_ulangan" :value="__('Nama Ulangan')" />
                                <x-text-input id="nama_ulangan" name="nama_ulangan" type="text" class="mt-1 block w-full" :value="old('nama_ulangan')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_ulangan')" />
                            </div>

                            {{-- Mata Pelajaran (sudah difilter) --}}
                            <div>
                                <x-input-label for="mata_pelajaran_id" :value="__('Mata Pelajaran')" />
                                {{-- PERBAIKAN STYLE DARK MODE DI SINI --}}
                                <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @forelse ($mataPelajarans as $mapel)
                                        <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada mata pelajaran untuk jenjang {{ $jenjang }}</option>
                                    @endforelse
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('mata_pelajaran_id')" />
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi (Opsional)')" />
                                {{-- PERBAIKAN STYLE DARK MODE DI SINI --}}
                                <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('deskripsi')" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <a href="{{ route('ulangan.index', ['jenjang' => $jenjang]) }}" class="text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan dan Lanjut') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
