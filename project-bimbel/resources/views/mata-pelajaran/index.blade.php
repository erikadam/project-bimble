<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Menampilkan notifikasi sukses atau error --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form untuk menambah mata pelajaran baru --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
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
                                <select name="jenjang_pendidikan" id="jenjang_pendidikan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="SD" @selected(request('jenjang') == 'SD')>SD</option>
                                    <option value="SMP" @selected(request('jenjang') == 'SMP')>SMP</option>
                                    <option value="SMA" @selected(request('jenjang') == 'SMA')>SMA</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-4">
                                <p class="text-sm text-gray-700">Tipe:</p>
                                <label class="flex items-center">
                                    <input type="radio" name="is_wajib" value="0" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ms-2">Opsional</span>
                                </label>
                                <label class="flex items-center">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Daftar Mata Pelajaran</h3>
                        {{-- Filter Jenjang --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Filter Jenjang:</span>
                            <select onchange="window.location.href = this.value" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="{{ route('mata-pelajaran.index') }}">Semua</option>
                                @foreach (['SD', 'SMP', 'SMA'] as $jenjangOption)
                                    <option value="{{ route('mata-pelajaran.index', ['jenjang' => $jenjangOption]) }}" @if($jenjang === $jenjangOption) selected @endif>
                                        {{ $jenjangOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <table class="min-w-full leading-normal">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Mata Pelajaran</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenjang</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mataPelajaran as $mapel)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                    <a href="{{ route('mata-pelajaran.soal.index', $mapel->id) }}" class="text-gray-900 hover:text-blue-600 font-semibold">
                                        {{ $mapel->nama_mapel }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $mapel->jenjang_pendidikan }}</td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                    @if ($mapel->is_wajib)
                                        <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">Wajib</span>
                                    @else
                                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">Opsional</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                    {{-- Aksi --}}
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('mata-pelajaran.edit', $mapel->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                        <form method="POST" action="{{ route('mata-pelajaran.destroy', $mapel->id) }}" onsubmit="return confirm('Yakin ingin menghapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Belum ada data mata pelajaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
