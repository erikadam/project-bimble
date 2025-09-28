<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Buat Halaman About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('about-us.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-300">Judul</label>
                            <input type="text" name="title" id="title" class="border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block font-medium text-sm text-gray-300 mb-2">Konten</label>
                            {{-- Textarea ini akan disembunyikan dan digantikan oleh TinyMCE --}}
                            <textarea name="content" id="content" class="hidden">{{ old('content') }}</textarea>
                        </div>



                        <div class="mb-4">
                            <label for="images" class="block font-medium text-sm text-gray-300">Gambar Slider (Bisa pilih lebih dari satu)</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600" multiple>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        // Konfigurasi TinyMCE yang sama persis dengan halaman "Create Soal"
        tinymce.init({
            selector: 'textarea#content',
            plugins: 'autolink lists link image charmap table',
            license_key: 'gpl',
            toolbar_mode: 'floating',
            height: 300,
            skin: 'oxide-dark',
            content_css: 'dark',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | table'
        });
    </script>
    @endpush

</x-app-layout>
