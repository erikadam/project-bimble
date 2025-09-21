<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Edit Poin') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">
                    <form action="{{ route('company-goals.update', $goal) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            {{-- Jenis Poin --}}
                            <div>
                                <x-input-label for="type" :value="__('Jenis Poin')" class="text-gray-300" />
                                <select name="type" id="type" class="mt-1 block w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm text-gray-200" required>
                                    <option value="visi" @if($goal->type == 'visi') selected @endif>Visi</option>
                                    <option value="misi" @if($goal->type == 'misi') selected @endif>Misi</option>
                                    <option value="tujuan" @if($goal->type == 'tujuan') selected @endif>Tujuan</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                            {{-- Judul --}}
                            <div>
                                <x-input-label for="title" :value="__('Judul (cth: Visi Utama, Misi 1, Tujuan 2)')" class="text-gray-300" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200" :value="old('title', $goal->title)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <x-input-label for="description" :value="__('Deskripsi')" class="text-gray-300" />
                                <textarea name="description" id="description" rows="4" class="mt-1 block w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm text-gray-200" required>{{ old('description', $goal->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('company-goals.index') }}" class="text-sm text-gray-400 hover:text-gray-200 underline">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
