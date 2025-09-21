<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Menampilkan notifikasi sukses atau error --}}
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-800 border-l-4 border-green-500 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-800 border-l-4 border-red-500 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Form untuk menambah mata pelajaran baru --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-4">Tambah Mata Pelajaran</h3>
                    <form action="{{ route('mata-pelajaran.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" />
                                <x-text-input type="text" id="nama_mapel" name="nama_mapel" class="w-full mt-1" placeholder="Contoh: Fisika" required />
                                <x-input-error :messages="$errors->get('nama_mapel')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jenjang_pendidikan" :value="__('Jenjang Pendidikan')" />
                                <select name="jenjang_pendidikan" id="jenjang_pendidikan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="SD" @selected(request('jenjang') == 'SD')>SD</option>
                                    <option value="SMP" @selected(request('jenjang') == 'SMP')>SMP</option>
                                    <option value="SMA" @selected(request('jenjang') == 'SMA')>SMA</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-4">
                                <p class="text-sm text-gray-700 dark:text-gray-400">Tipe:</p>
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="is_wajib" value="0" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">Opsional</span>
                                </label>
                                <label class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="is_wajib" value="1" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">Wajib</span>
                                </label>
                            </div>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Daftar Mata Pelajaran</h3>
                        {{-- Filter Jenjang --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Filter Jenjang:</span>
                            <select onchange="window.location.href = this.value" class="block w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="{{ route('mata-pelajaran.index') }}">Semua</option>
                                @foreach (['SD', 'SMP', 'SMA'] as $jenjangOption)
                                    <option value="{{ route('mata-pelajaran.index', ['jenjang' => $jenjangOption]) }}" @if($jenjang === $jenjangOption) selected @endif>
                                        {{ $jenjangOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Mata Pelajaran</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenjang</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($mataPelajaran as $mapel)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm">
                                        <a href="{{ route('mata-pelajaran.soal.index', $mapel->id) }}" class="text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 font-semibold">
                                            {{ $mapel->nama_mapel }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-900 dark:text-white">{{ $mapel->jenjang_pendidikan }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm">
                                        @if ($mapel->is_wajib)
                                            <span class="px-2 py-1 font-semibold leading-tight text-blue-700 dark:text-blue-200 bg-blue-100 dark:bg-blue-800 rounded-full">Wajib</span>
                                        @else
                                            <span class="px-2 py-1 font-semibold leading-tight text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-600 rounded-full">Opsional</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 text-sm">
                                        <div class="flex items-center space-x-4">
                                            <a href="{{ route('mata-pelajaran.edit', $mapel->id) }}" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 font-medium">Edit</a>
                                            <form method="POST" action="{{ route('mata-pelajaran.destroy', $mapel->id) }}" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada data mata pelajaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
