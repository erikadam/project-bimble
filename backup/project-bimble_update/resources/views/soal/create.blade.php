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
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">
                        <div>
                            <x-input-label for="pertanyaan" :value="__('Teks Pertanyaan')" class="text-gray-300" />
                            <textarea id="pertanyaan" name="pertanyaan" class="hidden">{{ old('pertanyaan') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <x-input-label for="tipe_soal" :value="__('Tipe Soal')" class="text-gray-300" />
                                <select id="tipe_soal" name="tipe_soal" class="block w-full mt-1 bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-600">
                                    <option value="pilihan_ganda">Pilihan Ganda</option>
                                    <option value="pilihan_ganda_majemuk">Pilihan Ganda Majemuk</option>
                                    <option value="isian">Isian Singkat</option>
                                    <option value="benar_salah_tabel">Benar/Salah (Tabel)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="tingkat_kesulitan" :value="__('Tingkat Kesulitan')" class="text-gray-300" />
                                <select id="tingkat_kesulitan" name="tingkat_kesulitan" class="block w-full mt-1 bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-600">
                                    <option value="Mudah">Mudah</option>
                                    <option value="Sedang" selected>Sedang</option>
                                    <option value="Sulit">Sulit</option>
                                </select>
                            </div>
                        </div>
                        <div id="opsi-jawaban-section" class="mt-6">
                            <h3 class="text-lg font-medium text-gray-300 mb-4" id="opsi-jawaban-title">Opsi Jawaban</h3>
                            <div id="pilihan-jawaban-wrapper" class="space-y-4"></div>
                            <button type="button" id="tambah-pilihan-btn" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Tambah Opsi</button>
                        </div>
                        <div id="form-benar-salah" class="mt-6" style="display: none;">
                             <h3 class="text-lg font-medium text-gray-300 mb-4">Daftar Pernyataan</h3>
                             <div class="space-y-4" id="pernyataan-container"></div>
                             <button type="button" id="add-pernyataan-btn" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Tambah Pernyataan</button>
                        </div>
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700">
                             <a href="{{ route('mata-pelajaran.soal.index', ['mata_pelajaran' => $mataPelajaran->id]) }}" class="text-sm text-gray-400 hover:text-white underline">Batal</a>
                            <x-primary-button class="ml-4">{{ __('Simpan Soal') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tinymceConfig = {
                selector: '.tinymce-pilihan',
                plugins: 'autolink lists link image charmap print preview hr anchor pagebreak table',
                toolbar_mode: 'floating', height: 150, menubar: false,
                toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | table',
                skin: 'oxide-dark', content_css: 'dark'
            };
            tinymce.init({
                selector: '#pertanyaan',
                plugins: 'autolink lists link image charmap print preview hr anchor pagebreak table',
                toolbar_mode: 'floating', height: 300, skin: 'oxide-dark', content_css: 'dark',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | table'
            });

            const tipeSoalSelect = document.getElementById('tipe_soal');
            const opsiJawabanSection = document.getElementById('opsi-jawaban-section');
            const formBenarSalah = document.getElementById('form-benar-salah');
            const pilihanWrapper = document.getElementById('pilihan-jawaban-wrapper');
            const pernyataanContainer = document.getElementById('pernyataan-container');
            const tambahPilihanBtn = document.getElementById('tambah-pilihan-btn');
            const addPernyataanBtn = document.getElementById('add-pernyataan-btn');
            let pilihanCount = 0;
            let pernyataanCount = 0;

            function toggleSections() {
                const tipe = tipeSoalSelect.value;
                const isTabel = tipe === 'benar_salah_tabel';
                opsiJawabanSection.style.display = isTabel ? 'none' : 'block';
                formBenarSalah.style.display = isTabel ? 'block' : 'none';
                opsiJawabanSection.querySelectorAll('input, textarea').forEach(el => el.disabled = isTabel);
                formBenarSalah.querySelectorAll('input, textarea').forEach(el => el.disabled = !isTabel);
            }

            function updatePilihanInputs() {
                const tipe = tipeSoalSelect.value;
                document.getElementById('opsi-jawaban-title').textContent = tipe === 'isian' ? 'Kunci Jawaban' : 'Opsi Jawaban';
                pilihanWrapper.querySelectorAll('.pilihan-item').forEach(item => {
                    const correctDiv = item.querySelector('.correct-answer-div');
                    if (correctDiv) correctDiv.style.display = tipe === 'isian' ? 'none' : 'flex';
                    const input = item.querySelector('input[id^="is_correct_"]');
                    if (input) {
                        const newType = (tipe === 'pilihan_ganda_majemuk') ? 'checkbox' : 'radio';
                        const newName = (tipe === 'pilihan_ganda') ? 'pilihan[0][benar]' : `pilihan[${input.id.split('_').pop()}][benar]`;
                        input.type = newType;
                        input.name = newName;
                    }
                });
            }

            function addPilihan() {
                const index = pilihanCount++;
                const newEditorId = `pilihan_teks_${index}`;
                const newPilihanDiv = document.createElement('div');
                newPilihanDiv.className = 'pilihan-item';
                newPilihanDiv.innerHTML = `
                    <div class="flex items-start space-x-4"><div class="flex-grow"><textarea id="${newEditorId}" name="pilihan[${index}][teks]" class="hidden tinymce-pilihan"></textarea></div>
                        <div class="flex-shrink-0 flex items-center space-x-4 pt-2">
                            <div class="correct-answer-div">
                                <input type="radio" id="is_correct_${index}" name="pilihan[${index}][benar]" class="h-4 w-4 text-indigo-500 border-gray-600 rounded focus:ring-indigo-500">
                                <label for="is_correct_${index}" class="ml-2 text-sm text-gray-300">Benar</label>
                            </div>
                            <button type="button" class="remove-pilihan text-red-500 hover:text-red-400 text-2xl leading-none">&times;</button>
                        </div>
                    </div>`;
                pilihanWrapper.appendChild(newPilihanDiv);
                if (window.tinymce) tinymce.init({ selector: `#${newEditorId}`, ...tinymceConfig });
                newPilihanDiv.querySelector('.remove-pilihan').addEventListener('click', () => {
                    tinymce.get(newEditorId).remove();
                    newPilihanDiv.remove();
                });
                updatePilihanInputs();
            }

            function addPernyataan() {
                const index = pernyataanCount++;
                const div = document.createElement('div');
                div.className = 'p-4 border border-gray-700 rounded-md bg-gray-900/50';
                div.innerHTML = `
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pernyataan #${index + 1}</label>
                    <textarea name="pernyataan[${index}][teks]" rows="2" class="block w-full bg-gray-900 border-gray-700 rounded-md shadow-sm text-gray-300"></textarea>
                    <div class="mt-3 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-300">Jawaban:</span>
                            <label class="ml-4"><input type="radio" name="pernyataan[${index}][jawaban]" value="benar" class="h-4 w-4" checked><span class="ml-2">Benar</span></label>
                            <label class="ml-4"><input type="radio" name="pernyataan[${index}][jawaban]" value="salah" class="h-4 w-4"><span class="ml-2">Salah</span></label>
                        </div>
                        <button type="button" class="remove-pernyataan text-red-500 hover:text-red-400 text-2xl leading-none">&times;</button>
                    </div>`;
                pernyataanContainer.appendChild(div);
                div.querySelector('.remove-pernyataan').addEventListener('click', () => div.remove());
            }

            tipeSoalSelect.addEventListener('change', () => {
                pilihanWrapper.innerHTML = ''; pilihanCount = 0; addPilihan(); addPilihan();
                toggleSections();
                updatePilihanInputs();
            });
            tambahPilihanBtn.addEventListener('click', addPilihan);
            addPernyataanBtn.addEventListener('click', addPernyataan);

            addPilihan(); addPilihan(); addPilihan(); addPilihan();
            addPernyataan(); addPernyataan();
            toggleSections();
        });
    </script>
</x-app-layout>
