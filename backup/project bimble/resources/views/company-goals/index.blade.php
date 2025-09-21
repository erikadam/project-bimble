<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Kelola Visi, Misi & Tujuan') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('company-goals.create') }}" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-2 px-4 rounded mb-6 inline-block">
                Tambah Poin Baru
            </a>

            @foreach(['Visi' => $visi, 'Misi' => $misi, 'Tujuan' => $tujuan] as $title => $items)
            <div class="mb-8">
                <h3 class="text-2xl font-semibold mb-4 text-gray-100">{{ $title }}</h3>
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-gray-800 border-b border-gray-700 text-gray-300">
                        @if($items->isEmpty())
                            <p class="text-gray-500">Belum ada data untuk {{ strtolower($title) }}.</p>
                        @else
                        <ul class="divide-y divide-gray-700">
                            @foreach($items as $item)
                            <li class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-200">{{ $item->title }}</p>
                                    <p class="text-sm text-gray-400">{{ $item->description }}</p>
                                </div>
                                <div>
                                    <a href="{{ route('company-goals.edit', $item) }}" class="text-yellow-400 hover:text-yellow-300">Edit</a>
                                    <form action="{{ route('company-goals.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Anda yakin?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 ml-4">Hapus</button>
                                    </form>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
