<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            Tambah Soal Baru untuk: {{ $mataPelajaran->nama_mapel }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-100">
                    <form method="POST" action="{{ route('mata-pelajaran.soal.store', ['mata_pelajaran' => $mataPelajaran->id]) }}" enctype="multipart/form-data" id="soal-form">
                        @csrf

                        {{-- Pertanyaan --}}
                        <div>
                            <x-input-label for="pertanyaan" :value="__('Teks Pertanyaan')" class="text-gray-300" />
                            {{-- Textarea ini akan diinisialisasi oleh TinyMCE --}}
                            <textarea id="pertanyaan" name="pertanyaan" class="hidden">{{ old('pertanyaan') }}</textarea>
                            <x-input-error :messages="$errors->get('pertanyaan')" class="mt-2" />
                        </div>

                        {{-- Gambar Soal (Opsional) --}}
                        <div class="mt-6">
                            <x-input-label for="gambar_soal" :value="__('Gambar Soal (Opsional)')" class="text-gray-300" />
                            <input id="gambar_soal" name="gambar_soal" type="file" class="block mt-1 w-full text-sm text-gray-300 border border-gray-600 rounded-md cursor-pointer bg-gray-700 focus:outline-none file:bg-gray-600 file:text-gray-200 file:border-0 file:py-2 file:px-4">
                            <x-input-error :messages="$errors->get('gambar_soal')" class="mt-2" />
                            <div id="gambar-soal-preview-container" class="hidden mt-2">
                                <p class="text-sm text-gray-400 mb-2">Pratinjau:</p>
                                <img src="#" alt="Pratinjau Gambar Soal" id="gambar-soal-preview" class="max-w-xs rounded-lg shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            {{-- Tipe Soal --}}
                            <div>
                                <x-input-label for="tipe_soal" :value="__('Tipe Soal')" class="text-gray-300" />
                                <select id="tipe_soal" name="tipe_soal" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="pilihan_ganda" @selected(old('tipe_soal') == 'pilihan_ganda')>Pilihan Ganda</option>
                                    <option value="pilihan_ganda_majemuk" @selected(old('tipe_soal') == 'pilihan_ganda_majemuk')>Pilihan Ganda Majemuk</option>
                                    <option value="isian" @selected(old('tipe_soal') == 'isian')>Isian Singkat</option>
                                </select>
                                <x-input-error :messages="$errors->get('tipe_soal')" class="mt-2" />
                            </div>

                            {{-- Status Soal --}}
                            <div>
                                <x-input-label for="status" :value="__('Status Soal')" class="text-gray-300" />
                                <select id="status" name="status" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-200 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                                    <option value="aktif" @selected(old('status', 'aktif') == 'aktif')>Aktif</option>
                                    <option value="nonaktif" @selected(old('status') == 'nonaktif')>Nonaktif</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Bagian Pilihan Jawaban (untuk Pilihan Ganda) --}}
                        <div id="pilihan-jawaban-section" class="mt-6 border-t border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-200 mb-2">Pilihan Jawaban</h3>
                            <x-input-error :messages="$errors->get('pilihan')" class="mb-2" />
                            <x-input-error :messages="$errors->get('jawaban_benar')" class="mb-2" />
                            <div id="pilihan-wrapper" class="space-y-4">
                                {{-- Pilihan akan ditambahkan oleh JavaScript --}}
                            </div>
                            <button type="button" id="tambah-pilihan" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">Tambah Opsi Jawaban</button>
                        </div>

                        {{-- Bagian Jawaban Isian (untuk Isian Singkat) --}}
                        <div id="jawaban-isian-section" class="mt-6 border-t border-gray-700 pt-6" style="display: none;">
                             <h3 class="text-lg font-medium text-gray-200 mb-2">Kunci Jawaban</h3>
                            <div>
                                <x-input-label for="jawaban_isian" :value="__('Jawaban Teks Singkat')" class="text-gray-300" />
                                <x-text-input id="jawaban_isian" class="block mt-1 w-full" type="text" name="jawaban_isian" :value="old('jawaban_isian')" />
                                <x-input-error :messages="$errors->get('jawaban_isian')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-8">
                             <a href="{{ route('mata-pelajaran.soal.index', $mataPelajaran->id) }}" class="text-sm text-gray-400 hover:text-gray-200 underline">
                                Batal
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Soal') }}
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

            tinymce.init({
                selector: '#pertanyaan',
                ...tinymceConfig
            });

            const tipeSoalSelect = document.getElementById('tipe_soal');
            const pilihanSection = document.getElementById('pilihan-jawaban-section');
            const isianSection = document.getElementById('jawaban-isian-section');
            const pilihanWrapper = document.getElementById('pilihan-wrapper');
            const tambahPilihanBtn = document.getElementById('tambah-pilihan');
            const gambarSoalInput = document.getElementById('gambar_soal');
            const gambarSoalPreview = document.getElementById('gambar-soal-preview');
            const gambarSoalPreviewContainer = document.getElementById('gambar-soal-preview-container');

            let pilihanCount = 0;
            let answerInputType = 'radio';

            function toggleSections() {
                if (tipeSoalSelect.value === 'isian') {
                    pilihanSection.style.display = 'none';
                    isianSection.style.display = 'block';
                } else {
                    pilihanSection.style.display = 'block';
                    isianSection.style.display = 'none';
                }
            }

            function toggleAnswerInputType() {
                answerInputType = (tipeSoalSelect.value === 'pilihan_ganda_majemuk') ? 'checkbox' : 'radio';
                document.querySelectorAll('input[name="jawaban_benar[]"], input[name="jawaban_benar"]').forEach(input => {
                    input.type = answerInputType;
                });
            }

            function setupImagePreview(input, previewImg, previewContainer) {
                input.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            previewImg.src = event.target.result;
                            previewContainer.style.display = 'block';
                        }
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }

            function addPilihan(isFirst = false) {
                 const newEditorId = `pilihan-editor-${pilihanCount}`;
                 const newPilihanDiv = document.createElement('div');
                 newPilihanDiv.className = 'p-4 border border-gray-700 rounded-lg bg-gray-900/50';
                 newPilihanDiv.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0 flex items-center h-full mt-1">
                            <input type="${answerInputType}" name="${answerInputType === 'radio' ? 'jawaban_benar' : 'jawaban_benar[]'}" value="${pilihanCount}" class="focus:ring-yellow-500 h-4 w-4 text-yellow-600 border-gray-500 bg-gray-700">
                        </div>
                        <div class="ms-4 flex-grow">
                             <label for="${newEditorId}" class="block text-sm font-medium text-gray-300 mb-1">Teks Opsi Jawaban ${pilihanCount + 1}</label>
                             <textarea id="${newEditorId}" name="pilihan[${pilihanCount}]" class="pilihan-editor hidden"></textarea>
                        </div>
                        ${!isFirst ? `<button type="button" class="remove-pilihan ms-4 text-red-500 hover:text-red-400">&times;</button>` : ''}
                    </div>
                    <div class="mt-4 pl-8">
                        <label for="gambar_pilihan_${pilihanCount}" class="block text-sm font-medium text-gray-300">Gambar Opsi (Opsional)</label>
                         <input id="gambar_pilihan_${pilihanCount}" name="gambar_pilihan[${pilihanCount}]" type="file" class="gambar-pilihan-input block mt-1 w-full text-sm text-gray-300 border border-gray-600 rounded-md cursor-pointer bg-gray-700 focus:outline-none file:bg-gray-600 file:text-gray-200 file:border-0 file:py-2 file:px-4">
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

                if (!isFirst) {
                    newPilihanDiv.querySelector('.remove-pilihan').addEventListener('click', () => newPilihanDiv.remove());
                }

                pilihanCount++;
            }

            // Inisialisasi awal
            tipeSoalSelect.addEventListener('change', () => {
                toggleSections();
                toggleAnswerInputType();
            });

            tambahPilihanBtn.addEventListener('click', () => addPilihan());

            // Tambah dua pilihan default saat halaman dimuat
            addPilihan(true);
            addPilihan(true);

            setupImagePreview(gambarSoalInput, gambarSoalPreview, gambarSoalPreviewContainer);

            toggleSections();
            toggleAnswerInputType();
        });
    </script>
</x-app-layout>
