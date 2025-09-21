<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PATCH') {{-- Method untuk update --}}

                        <div>
                            <x-input-label for="name" :value="__('Nama')" class="text-gray-300"/>
                            <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" class="text-gray-300"/>
                            <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <p class="text-sm text-gray-400 mt-6 border-t border-gray-700 pt-6">
                            Kosongkan isian password jika Anda tidak ingin mengubahnya.
                        </p>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password Baru')" class="text-gray-300"/>
                            <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200"
                                            type="password"
                                            name="password"
                                            autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-gray-300"/>
                            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 focus:border-yellow-500 focus:ring-yellow-500 text-gray-200"
                                            type="password"
                                            name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-400 hover:text-gray-200 underline">Batal</a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
