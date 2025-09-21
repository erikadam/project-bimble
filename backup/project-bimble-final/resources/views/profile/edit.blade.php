<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-gray-300 text-sm font-bold mb-2">
                                Nama:
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="shadow appearance-none border border-gray-600 bg-gray-900 rounded w-full py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline focus:border-yellow-500" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="email" class="block text-gray-300 text-sm font-bold mb-2">
                                Email:
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="shadow appearance-none border border-gray-600 bg-gray-900 rounded w-full py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline focus:border-yellow-500" required>
                            @error('email')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('users.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-400 hover:text-gray-200">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
