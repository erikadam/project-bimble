<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Edit About Us') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">

                    @if(session('info'))
                        <div class="mb-4 bg-blue-900/50 border border-blue-700 text-blue-300 px-4 py-3 rounded-lg">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('about-us.update', $aboutUs->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block font-medium text-sm text-gray-300">Judul</label>
                            <input type="text" name="title" id="title" class="border-gray-700 bg-gray-900 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm block w-full mt-1" value="{{ old('title', $aboutUs->title) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block font-medium text-sm text-gray-300 mb-2">Konten</label>
                            <textarea name="content" id="content" class="hidden">{{ old('content', $aboutUs->content) }}</textarea>
                        </div>


                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-300">Gambar Slider Saat Ini</label>
                            @if($aboutUs->images->isNotEmpty())
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($aboutUs->images as $image)
                                        <div class="relative group">
                                            <img src="{{ Storage::url($image->image_path) }}" alt="Slider Image" class="w-full h-32 object-cover rounded-md">
                                            <div class="absolute top-1 right-1">
                                                <label for="delete_images_{{ $image->id }}" class="flex items-center bg-gray-900/70 p-1 rounded cursor-pointer">
                                                    <input type="checkbox" name="delete_images[]" id="delete_images_{{ $image->id }}" value="{{ $image->id }}" class="h-4 w-4 text-red-600 bg-gray-700 border-gray-600 focus:ring-red-500 rounded">
                                                    <span class="ml-2 text-xs text-red-400 font-bold">Hapus</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-1">Belum ada gambar slider.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="images" class="block font-medium text-sm text-gray-300">Tambah Gambar Slider Baru (Bisa pilih lebih dari satu)</label>
                            <input type="file" name="images[]" id="images" class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600" multiple>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700">
                             <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">
                                Simpan Perubahan
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
