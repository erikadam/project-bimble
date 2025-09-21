<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Ujian') }}: {{ $paketTryout->nama_paket }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 text-center">
                    <h3 class="text-2xl font-bold mb-4">Ujian Selesai!</h3>
                    <p class="text-lg text-gray-600 mb-6">Berikut adalah hasil pengerjaan demo Anda.</p>

                    <div class="flex flex-col items-center space-y-4">
                        <div class="text-6xl font-bold text-indigo-600">{{ number_format($skor, 2) }}</div>
                        <p class="text-xl font-medium">Skor Anda</p>
                    </div>

                    <div class="mt-8 flex justify-center space-x-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $totalBenar }}</div>
                            <p class="text-sm text-gray-500">Jawaban Benar</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600">{{ $totalSoal - $totalBenar }}</div>
                            <p class="text-sm text-gray-500">Jawaban Salah</p>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800">{{ $totalSoal }}</div>
                            <p class="text-sm text-gray-500">Total Soal</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('dashboard') }}" class="inline-block text-sm text-gray-600 hover:text-gray-900 font-medium">
                    &larr; Kembali ke Dasbor
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
