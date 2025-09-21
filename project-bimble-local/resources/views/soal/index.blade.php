{{-- resources/views/soal/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Bank Soal: <span class="text-blue-400">{{ $mataPelajaran->nama_mapel }}</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Jenjang {{ $mataPelajaran->jenjang_pendidikan }}
                </p>
            </div>
            <div class="flex items-center space-x-3 mt-4 sm:mt-0">
                <a href="{{ route('soal.pilih_mapel', ['jenjang' => $mataPelajaran->jenjang_pendidikan]) }}" class="text-sm bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                    Kembali
                </a>
                <a href="{{ route('mata-pelajaran.soal.create', ['mata_pelajaran' => $mataPelajaran->id]) }}" class="inline-block bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-2 px-4 rounded-lg transition duration-300">
                    + Tambah Soal Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                        <div class="mb-4">
                        {{ $soalItems->links() }}
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Pertanyaan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tipe
                                    </th>
                                    {{-- KOLOM BARU DITAMBAHKAN DI SINI --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Rating
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($soalItems as $soal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $loop->iteration + ($soalItems->currentPage() - 1) * $soalItems->perPage() }}
                                        </td>
                                        <td class="px-6 py-4 max-w-md">
                                            <div class="text-sm text-gray-900 dark:text-gray-100 prose prose-sm dark:prose-invert max-w-none">
                                                {!! $soal->pertanyaan !!}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $soal->tipe_soal }}
                                        </td>
                                        {{-- ISI KOLOM BARU DITAMBAHKAN DI SINI --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @php
                                                $rating = $soal->tingkat_kesulitan ?? 'Belum ada';
                                                $colorClass = '';
                                                switch (strtolower($rating)) {
                                                    case 'mudah':
                                                        $colorClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                                        break;
                                                    case 'sedang':
                                                        $colorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                                        break;
                                                    case 'sulit':
                                                        $colorClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                                        break;
                                                    default:
                                                        $colorClass = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                                                        break;
                                                }
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ $rating }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('soal.show', $soal->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">Detail</a>
                                                <a href="{{ route('soal.edit', $soal->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit</a>
                                                <form action="{{ route('soal.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            Belum ada soal untuk mata pelajaran ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                        <div class="mb-4">
                            {{ $soalItems->links() }}
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
