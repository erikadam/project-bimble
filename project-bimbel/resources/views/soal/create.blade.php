<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Soal Baru untuk: {{ $mataPelajaran->nama_mapel }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    <form method="POST" action="{{ route('mata-pelajaran.soal.store', ['mata_pelajaran' => $mataPelajaran->id]) }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Pertanyaan --}}
                        <div>
                            <x-input-label for="pertanyaan" :value="__('Teks Pertanyaan')" />
                            <textarea id="pertanyaan" name="pertanyaan" rows="10">{{ old('pertanyaan') }}</textarea>
                            <x-input-error :messages="$errors->get('pertanyaan')" class="mt-2" />
                        </div>

                        {{-- Gambar Soal (Opsional) --}}
                        <div class="mt-4">
                            <x-input-label for="gambar_soal" :value="__('Gambar Soal (Opsional)')" />
                            <input id="gambar_soal" name="gambar_soal" type="file" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <x-input-error :messages="$errors->get('gambar_soal')" class="mt-2" />
                            <div id="gambar_soal_preview_container" class="hidden mt-2">
                                <p class="text-sm text-gray-600 mb-2">Pratinjau:</p>
                                <img id="gambar_soal_preview" src="#" alt="Pratinjau Gambar Soal" class="max-w-xs rounded-lg shadow-sm">
                            </div>
                        </div>

                        {{-- Tipe Soal --}}
                        <div class="mt-4">
                            <x-input-label for="tipe_soal" :value="__('Tipe Soal')" />
                            <select name="tipe_soal" id="tipe_soal" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="pilihan_ganda" @selected(old('tipe_soal', 'pilihan_ganda') == 'pilihan_ganda')>Pilihan Ganda (Satu Jawaban)</option>
                                <option value="pilihan_ganda_majemuk" @selected(old('tipe_soal') == 'pilihan_ganda_majemuk')>Pilihan Ganda (Banyak Jawaban)</option>
                                <option value="isian" @selected(old('tipe_soal') == 'isian')>Isian Singkat</option>
                            </select>
                        </div>

                        {{-- Status Soal --}}
                        <div class="mt-4">
                            <x-input-label for="status" value="Status Soal" />
                            <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="aktif" @selected(old('status', 'aktif') == 'aktif')>Aktif</option>
                                <option value="nonaktif" @selected(old('status') == 'nonaktif')>Nonaktif</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <hr class="my-6">

                        {{-- OPSI JAWABAN (DINAMIS) --}}
                        <div id="opsi-jawaban-container">
                            <div id="pilihan-ganda-section">
                                <h3 class="text-lg font-medium mb-2">Opsi Pilihan Ganda</h3>
                                <p class="text-sm text-gray-600 mb-4">Tandai pilihan sebagai jawaban yang benar. Pilihan dapat berupa teks atau gambar.</p>
                                <div id="pilihan-wrapper" class="space-y-3">
                                    {{-- Opsi 1 --}}
                                    <div class="flex items-start space-x-3">
                                        <input type="radio" name="jawaban_benar" value="0" @if(old('jawaban_benar') == 0 || (is_array(old('jawaban_benar')) && in_array(0, old('jawaban_benar')))) checked @endif class="jawaban-benar-input text-indigo-600 focus:ring-indigo-500 mt-2">
                                        <div class="w-full">
                                            <textarea name="pilihan[]" class="editor-pilihan w-full mb-2" rows="3" placeholder="Opsi A">{{ old('pilihan.0') }}</textarea>
                                            <input type="file" name="gambar_pilihan[0]" class="gambar-pilihan-input w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                            <div class="gambar-pilihan-preview-container hidden mt-2">
                                                <p class="text-sm text-gray-600 mb-2">Pratinjau:</p>
                                                <img src="#" alt="Pratinjau Gambar Opsi" class="gambar-pilihan-preview max-w-xs rounded-lg shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Opsi 2 --}}
                                    <div class="flex items-start space-x-3">
                                        <input type="radio" name="jawaban_benar" value="1" @if(old('jawaban_benar') == 1 || (is_array(old('jawaban_benar')) && in_array(1, old('jawaban_benar')))) checked @endif class="jawaban-benar-input text-indigo-600 focus:ring-indigo-500 mt-2">
                                        <div class="w-full">
                                            <textarea name="pilihan[]" class="editor-pilihan w-full mb-2" rows="3" placeholder="Opsi B">{{ old('pilihan.1') }}</textarea>
                                            <input type="file" name="gambar_pilihan[1]" class="gambar-pilihan-input w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                            <div class="gambar-pilihan-preview-container hidden mt-2">
                                                <p class="text-sm text-gray-600 mb-2">Pratinjau:</p>
                                                <img src="#" alt="Pratinjau Gambar Opsi" class="gambar-pilihan-preview max-w-xs rounded-lg shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="tambah-pilihan" class="mt-4 text-sm text-blue-600 hover:text-blue-800">+ Tambah Opsi</button>

                                @if ($errors->has('pilihan.*'))
                                    @foreach ($errors->get('pilihan.*') as $messages)
                                        @foreach ($messages as $message)
                                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                        @endforeach
                                    @endforeach
                                @endif
                                <x-input-error :messages="$errors->get('jawaban_benar')" class="mt-2" />
                            </div>

                            <div id="isian-section" style="display: none;">
                                <h3 class="text-lg font-medium mb-2">Jawaban Isian Singkat</h3>
                                <p class="text-sm text-gray-600 mb-4">Masukkan jawaban yang dianggap benar.</p>
                                <x-text-input type="text" name="jawaban_isian" class="w-full" :value="old('jawaban_isian')" />
                                <x-input-error :messages="$errors->get('jawaban_isian')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Simpan Soal') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const tinymceConfig = {
            selector: 'textarea#pertanyaan, textarea.editor-pilihan',
            plugins: 'autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount mathlive',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | superscript subscript | alignleft aligncenter alignright | bullist numlist | link image | mathlive | charmap | code | removeformat',
            height: 300,
            entity_encoding: 'raw',
            menubar: false,
            external_plugins: {
                'mathlive': 'https://unpkg.com/tinymce-mathlive@latest/dist/plugin.min.js'
            },
            custom_elements: 'math-field',
        };

        tinymce.init(tinymceConfig);

        document.addEventListener('DOMContentLoaded', function () {
            const tipeSoalSelect = document.getElementById('tipe_soal');
            const pilihanGandaSection = document.getElementById('pilihan-ganda-section');
            const isianSection = document.getElementById('isian-section');
            const tambahPilihanBtn = document.getElementById('tambah-pilihan');
            const pilihanWrapper = document.getElementById('pilihan-wrapper');
            const gambarSoalInput = document.getElementById('gambar_soal');
            const gambarSoalPreviewContainer = document.getElementById('gambar_soal_preview_container');
            const gambarSoalPreview = document.getElementById('gambar_soal_preview');

            let pilihanCount = 2;

            function setupImagePreview(inputFile, previewImg, previewContainer) {
                inputFile.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.style.display = 'none';
                        previewImg.src = '#';
                    }
                });
            }

            function toggleSections() {
                const tipe = tipeSoalSelect.value;
                if (tipe === 'pilihan_ganda' || tipe === 'pilihan_ganda_majemuk') {
                    pilihanGandaSection.style.display = 'block';
                    isianSection.style.display = 'none';
                } else {
                    pilihanGandaSection.style.display = 'none';
                    isianSection.style.display = 'block';
                }
            }

            function toggleAnswerInputType() {
                const isMajemuk = tipeSoalSelect.value === 'pilihan_ganda_majemuk';
                const inputs = pilihanWrapper.querySelectorAll('.jawaban-benar-input');
                inputs.forEach(input => {
                    input.type = isMajemuk ? 'checkbox' : 'radio';
                    input.name = isMajemuk ? 'jawaban_benar[]' : 'jawaban_benar';
                });
            }

            tipeSoalSelect.addEventListener('change', function() {
                toggleSections();
                toggleAnswerInputType();
            });

            tambahPilihanBtn.addEventListener('click', function () {
                const newPilihanDiv = document.createElement('div');
                newPilihanDiv.className = 'flex items-start space-x-3';

                const isMajemuk = tipeSoalSelect.value === 'pilihan_ganda_majemuk';
                const inputType = isMajemuk ? 'checkbox' : 'radio';
                const inputName = isMajemuk ? 'jawaban_benar[]' : 'jawaban_benar';
                const newEditorId = `editor-pilihan-${pilihanCount}`;

                newPilihanDiv.innerHTML = `
                    <input type="${inputType}" name="${inputName}" value="${pilihanCount}" class="jawaban-benar-input text-indigo-600 focus:ring-indigo-500 mt-2">
                    <div class="w-full">
                        <textarea id="${newEditorId}" name="pilihan[]" class="editor-pilihan w-full mb-2" rows="3" placeholder="Opsi ${String.fromCharCode(65 + pilihanCount)}"></textarea>
                        <input type="file" name="gambar_pilihan[${pilihanCount}]" class="gambar-pilihan-input w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <div class="gambar-pilihan-preview-container hidden mt-2">
                            <p class="text-sm text-gray-600 mb-2">Pratinjau:</p>
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

            setupImagePreview(gambarSoalInput, gambarSoalPreview, gambarSoalPreviewContainer);

            document.querySelectorAll('.gambar-pilihan-input').forEach(input => {
                const previewImg = input.closest('div').querySelector('.gambar-pilihan-preview');
                const previewContainer = input.closest('div').querySelector('.gambar-pilihan-preview-container');
                setupImagePreview(input, previewImg, previewContainer);
            });

            toggleSections();
            toggleAnswerInputType();
        });
    </script>
</x-app-layout>
