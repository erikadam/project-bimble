<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Edit Soal: {{ Str::limit(strip_tags($soal->pertanyaan), 30) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">
                    <form method="POST" action="{{ route('soal.update', $soal->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Pertanyaan --}}
                        <div>
                            <x-input-label for="pertanyaan" :value="__('Teks Pertanyaan')" class="text-gray-300" />
                            <textarea id="pertanyaan" name="pertanyaan" class="hidden">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                            <x-input-error :messages="$errors->get('pertanyaan')" class="mt-2" />
                        </div>

                        {{-- Gambar Soal --}}
                        <div class="mt-6">
                            <x-input-label for="gambar_soal" :value="__('Ganti Gambar Soal (Opsional)')" class="text-gray-300" />
                            @if ($soal->gambar_path)
                                <div id="current_gambar_soal_container" class="mt-2">
                                    <p class="text-sm text-gray-400 mb-2">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $soal->gambar_path) }}" alt="Gambar Soal" class="max-w-xs rounded-lg shadow-sm">
                                </div>
                            @endif
                             <input id="gambar_soal" name="gambar_soal" type="file" class="block mt-2 w-full text-sm text-gray-300 border border-gray-600 rounded-md cursor-pointer bg-gray-700 focus:outline-none file:bg-gray-600 file:text-gray-200 file:border-0 file:py-2 file:px-4">
                            <x-input-error :messages="$errors->get('gambar_soal')" class="mt-2" />
                             <div id="gambar-soal-preview-container" class="hidden mt-2">
                                <p class="text-sm text-gray-400 mb-2">Pratinjau baru:</p>
                                <img src="#" alt="Pratinjau Gambar Soal" id="gambar-soal-preview" class="max-w-xs rounded-lg shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            {{-- Tipe Soal --}}
                            <div>
                                <x-input-label for="tipe_soal" :value="__('Tipe Soal')" class="text-gray-300"/>
                                <select id="tipe_soal" name="tipe_soal" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="pilihan_ganda" @selected(old('tipe_soal', $soal->tipe_soal) == 'pilihan_ganda')>Pilihan Ganda</option>
                                    <option value="pilihan_ganda_majemuk" @selected(old('tipe_soal', $soal->tipe_soal) == 'pilihan_ganda_majemuk')>Pilihan Ganda Majemuk</option>
                                    <option value="isian" @selected(old('tipe_soal', $soal->tipe_soal) == 'isian')>Isian Singkat</option>
                                </select>
                                <x-input-error :messages="$errors->get('tipe_soal')" class="mt-2" />
                            </div>

                            {{-- Status Soal --}}
                            <div>
                                <x-input-label for="status" :value="__('Status Soal')" class="text-gray-300" />
                                <select id="status" name="status" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="aktif" @selected(old('status', $soal->status) == 'aktif')>Aktif</option>
                                    <option value="nonaktif" @selected(old('status', $soal->status) == 'nonaktif')>Nonaktif</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Pilihan Jawaban (untuk Pilihan Ganda) --}}
                        <div id="pilihan-jawaban-section" class="mt-6 border-t border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-200 mb-2">Pilihan Jawaban</h3>
                            <x-input-error :messages="$errors->get('pilihan')" class="mb-2" />
                            <x-input-error :messages="$errors->get('jawaban_benar')" class="mb-2" />
                            <div id="pilihan-wrapper" class="space-y-4">
                                @if ($soal->tipe_soal == 'pilihan_ganda' || $soal->tipe_soal == 'pilihan_ganda_majemuk')
                                    @php
                                        $jawabanBenar = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('id')->map(fn($id) => (string)$id)->toArray();
                                    @endphp
                                    @foreach ($soal->pilihanJawaban as $index => $pilihan)
                                        <div class="p-4 border border-gray-700 rounded-lg bg-gray-900/50">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 flex items-center h-full mt-1">
                                                    @if ($soal->tipe_soal == 'pilihan_ganda')
                                                        <input type="radio" name="jawaban_benar" value="{{ $pilihan->id }}" @checked(in_array((string)$pilihan->id, $jawabanBenar)) class="focus:ring-yellow-500 h-4 w-4 text-yellow-600 border-gray-500 bg-gray-700">
                                                    @else
                                                         <input type="checkbox" name="jawaban_benar[]" value="{{ $pilihan->id }}" @checked(in_array((string)$pilihan->id, $jawabanBenar)) class="focus:ring-yellow-500 h-4 w-4 text-yellow-600 border-gray-500 bg-gray-700 rounded">
                                                    @endif
                                                </div>
                                                <div class="ms-4 flex-grow">
                                                    <label for="pilihan-editor-{{$index}}" class="block text-sm font-medium text-gray-300 mb-1">Teks Opsi Jawaban {{ $index + 1 }}</label>
                                                    <textarea id="pilihan-editor-{{$index}}" name="pilihan[{{ $pilihan->id }}]" class="pilihan-editor hidden">{{ old("pilihan.$pilihan->id", $pilihan->pilihan_teks) }}</textarea>
                                                </div>
                                                <button type="button" class="remove-pilihan ms-4 text-red-500 hover:text-red-400">&times;</button>
                                            </div>
                                             <div class="mt-4 pl-8">
                                                <label for="gambar_pilihan_{{$index}}" class="block text-sm font-medium text-gray-300">Ganti Gambar Opsi (Opsional)</label>
                                                @if ($pilihan->gambar_path)
                                                    <div id="current_gambar_pilihan_container_{{$index}}" class="mt-2">
                                                        <p class="text-sm text-gray-400 mb-2">Gambar saat ini:</p>
                                                        <img src="{{ asset('storage/' . $pilihan->gambar_path) }}" alt="Gambar Pilihan" class="max-w-xs rounded-lg shadow-sm">
                                                    </div>
                                                @endif
                                                 <input id="gambar_pilihan_{{$index}}" name="gambar_pilihan[{{ $pilihan->id }}]" type="file" class="gambar-pilihan-input block mt-1 w-full text-sm text-gray-300 border border-gray-600 rounded-md cursor-pointer bg-gray-700 focus:outline-none file:bg-gray-600 file:text-gray-200 file:border-0 file:py-2 file:px-4">
                                                 <div class="gambar-pilihan-preview-container hidden mt-2">
                                                     <p class="text-sm text-gray-400 mb-2">Pratinjau baru:</p>
                                                    <img src="#" alt="Pratinjau Gambar Opsi" class="gambar-pilihan-preview max-w-xs rounded-lg shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                             <button type="button" id="tambah-pilihan" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">Tambah Opsi Jawaban</button>
                        </div>

                        {{-- Jawaban Isian (untuk Isian Singkat) --}}
                        <div id="jawaban-isian-section" class="mt-6 border-t border-gray-700 pt-6" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-200 mb-2">Kunci Jawaban</h3>
                            <div>
                                <x-input-label for="jawaban_isian" :value="__('Jawaban Teks Singkat')" class="text-gray-300"/>
                                <x-text-input id="jawaban_isian" class="block mt-1 w-full" type="text" name="jawaban_isian" :value="old('jawaban_isian', $soal->tipe_soal == 'isian' ? $soal->pilihanJawaban->first()->pilihan_teks : '')" />
                                <x-input-error :messages="$errors->get('jawaban_isian')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                             <a href="{{ route('soal.show', $soal->id) }}" class="text-sm text-gray-400 hover:text-gray-200 underline">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     @push('scripts')
        <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tinymceConfig = {
                api_key: '{{ config('services.tinymce.key') }}',
                plugins: 'lists link image table code help wordcount mathtype',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | mathtype',
                external_plugins: {
                    'mathtype': '/node_modules/@wiris/mathtype-tinymce6/plugin.min.js'
                },
                skin: 'oxide-dark',
                content_css: 'dark'
            };
            tinymce.init({ selector: '#pertanyaan', ...tinymceConfig });
            document.querySelectorAll('.pilihan-editor').forEach(editor => {
                 tinymce.init({ selector: `#${editor.id}`, ...tinymceConfig });
            });

            const tipeSoalSelect = document.getElementById('tipe_soal');
            const pilihanSection = document.getElementById('pilihan-jawaban-section');
            const isianSection = document.getElementById('jawaban-isian-section');
            const pilihanWrapper = document.getElementById('pilihan-wrapper');
            const tambahPilihanBtn = document.getElementById('tambah-pilihan');
            const gambarSoalInput = document.getElementById('gambar_soal');
            const gambarSoalPreview = document.getElementById('gambar-soal-preview');
            const gambarSoalPreviewContainer = document.getElementById('gambar-soal-preview-container');

            let pilihanCount = {{ $soal->pilihanJawaban->count() }};
            let answerInputType = '{{ $soal->tipe_soal === "pilihan_ganda_majemuk" ? "checkbox" : "radio" }}';

            function toggleSections() {
                const tipe = tipeSoalSelect.value;
                if (tipe === 'isian') {
                    pilihanSection.style.display = 'none';
                    isianSection.style.display = 'block';
                } else {
                    pilihanSection.style.display = 'block';
                    isianSection.style.display = 'none';
                }
            }

            function toggleAnswerInputType() {
                const tipe = tipeSoalSelect.value;
                answerInputType = (tipe === 'pilihan_ganda_majemuk') ? 'checkbox' : 'radio';
                const name = (tipe === 'pilihan_ganda_majemuk') ? 'jawaban_benar[]' : 'jawaban_benar';
                document.querySelectorAll('input[type="radio"][name^="jawaban_benar"], input[type="checkbox"][name^="jawaban_benar"]').forEach(input => {
                    input.type = answerInputType;
                    input.name = name;
                });
            }

            function setupImagePreview(input, previewImg, previewContainer, currentImgContainer = null) {
                input.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            previewImg.src = event.target.result;
                            previewContainer.style.display = 'block';
                            if (currentImgContainer) currentImgContainer.style.display = 'none';
                        }
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }

            pilihanWrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-pilihan')) {
                    e.target.closest('.p-4').remove();
                }
            });

            tambahPilihanBtn.addEventListener('click', function() {
                 const newIndex = 'new_' + pilihanCount;
                 const newEditorId = `pilihan-editor-${newIndex}`;
                 const newPilihanDiv = document.createElement('div');
                 newPilihanDiv.className = 'p-4 border border-gray-700 rounded-lg bg-gray-900/50';
                 newPilihanDiv.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0 flex items-center h-full mt-1">
                            <input type="${answerInputType}" name="${answerInputType === 'radio' ? 'jawaban_benar' : 'jawaban_benar[]'}" value="${newIndex}" class="focus:ring-yellow-500 h-4 w-4 text-yellow-600 border-gray-500 bg-gray-700 ${answerInputType === 'checkbox' ? 'rounded' : ''}">
                        </div>
                        <div class="ms-4 flex-grow">
                             <label for="${newEditorId}" class="block text-sm font-medium text-gray-300 mb-1">Teks Opsi Jawaban Baru</label>
                             <textarea id="${newEditorId}" name="pilihan[${newIndex}]" class="pilihan-editor hidden"></textarea>
                        </div>
                        <button type="button" class="remove-pilihan ms-4 text-red-500 hover:text-red-400">&times;</button>
                    </div>
                    <div class="mt-4 pl-8">
                        <label for="gambar_pilihan_${newIndex}" class="block text-sm font-medium text-gray-300">Gambar Opsi (Opsional)</label>
                         <input id="gambar_pilihan_${newIndex}" name="gambar_pilihan[${newIndex}]" type="file" class="gambar-pilihan-input block mt-1 w-full text-sm text-gray-300 border border-gray-600 rounded-md cursor-pointer bg-gray-700 focus:outline-none file:bg-gray-600 file:text-gray-200 file:border-0 file:py-2 file:px-4">
                        <div class="gambar-pilihan-preview-container hidden mt-2">
                            <p class="text-sm text-gray-400 mb-2">Pratinjau:</p>
                            <img src="#" alt="Pratinjau Gambar Opsi" class="gambar-pilihan-preview max-w-xs rounded-lg shadow-sm">
                        </div>
                    </div>
                `;
                pilihanWrapper.appendChild(newPilihanDiv);

                tinymce.init({ selector: `#${newEditorId}`, ...tinymceConfig });

                const newImageInput = newPilihanDiv.querySelector('.gambar-pilihan-input');
                const newImagePreview = newPilihanDiv.querySelector('.gambar-pilihan-preview');
                const newImagePreviewContainer = newPilihanDiv.querySelector('.gambar-pilihan-preview-container');
                setupImagePreview(newImageInput, newImagePreview, newImagePreviewContainer);

                pilihanCount++;
            });

            tipeSoalSelect.addEventListener('change', () => {
                toggleSections();
                toggleAnswerInputType();
            });

            setupImagePreview(gambarSoalInput, gambarSoalPreview, gambarSoalPreviewContainer, document.getElementById('current_gambar_soal_container'));
            document.querySelectorAll('.gambar-pilihan-input').forEach((input) => {
                const container = input.closest('.pl-8');
                const previewContainer = container.querySelector('.gambar-pilihan-preview-container');
                if (previewContainer) {
                    const previewImg = previewContainer.querySelector('.gambar-pilihan-preview');
                    const currentImgContainer = container.querySelector('[id^="current_gambar_pilihan_container_"]');
                    setupImagePreview(input, previewImg, previewContainer, currentImgContainer);
                }
            });

            toggleSections();
        });
    </script>
</x-app-layout>
