<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Poin Baru') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('company-goals.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="type" class="block">Jenis Poin</label>
                            <select name="type" id="type" class="w-full rounded" required>
                                <option value="visi">Visi</option>
                                <option value="misi">Misi</option>
                                <option value="tujuan">Tujuan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="title" class="block">Judul</label>
                            <input type="text" name="title" id="title" class="w-full rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block">Deskripsi</label>
                            <textarea name="description" id="description" rows="4" class="w-full rounded" required></textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
