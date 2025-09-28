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
                    @if ($errors->any())
                        <div class="mb-4 bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('mata-pelajaran.soal.store', $mataPelajaran->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">

                        <div class="mb-4">
                            <label for="pertanyaan" class="block font-medium text-sm text-gray-300 mb-2">Pertanyaan / Stimulus</label>
                            <textarea id="pertanyaan" name="pertanyaan" class="hidden">{{ old('pertanyaan') }}</textarea>
                        </div>

                        <div class="mb-4">
                             <label class="block font-medium text-sm text-gray-300">Gambar Soal (Opsional)</label>
                             <label for="gambar_soal" class="mt-1 flex items-center justify-center w-full h-32 px-4 transition bg-gray-900 border-2 border-gray-700 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-500">
                                 <div id="gambar-text-container" class="text-center">
                                     <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 16a4 4 0 01-4-4V6a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4H7z" /></svg>
                                     <span class="font-medium text-gray-500">Klik atau seret file</span>
                                 </div>
                                 <img id="gambar-preview" class="hidden h-full w-full object-contain rounded" alt="Image Preview">
                             </label>
                             <input type="file" id="gambar_soal" name="gambar_soal" class="hidden" accept="image/*">
                        </div>

                        <div class="mt-6">
                            <x-input-label for="tipe_soal" :value="__('Tipe Soal')" />
                            <select id="tipe_soal" name="tipe_soal" class="block w-full mt-1 bg-gray-900 border-gray-700 text-gray-300 rounded-md shadow-sm">
                                <option value="pilihan_ganda">Pilihan Ganda</option>
                                <option value="pilihan_ganda_majemuk">Pilihan Ganda Majemuk</option>
                                <option value="isian">Isian Singkat</option>
                                <option value="pilihan_ganda_kompleks">Pilihan Ganda Kompleks (Matriks)</option>
                            </select>
                        </div>

                        <hr class="my-6 border-gray-700">

                        <div id="form-pg-isian" class="space-y-4" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-300" id="opsi-jawaban-title">Opsi Jawaban</h3>
                            <div id="pilihan-jawaban-container" class="space-y-4"></div>
                            <button type="button" id="tambah-pilihan-btn" class="mt-2 text-sm text-blue-500 font-semibold">+ Tambah Pilihan</button>
                        </div>

                        <div id="form-kompleks" class="space-y-4" style="display: none;">
                             <h3 class="text-lg font-medium text-gray-300">Pengaturan Soal Matriks</h3>
                             <div class="overflow-x-auto p-1 bg-gray-900/50 rounded-lg">
                                 <table class="min-w-full">
                                     <thead id="matriks-kolom-header"></thead>
                                     <tbody id="matriks-body" class="divide-y divide-gray-700"></tbody>
                                 </table>
                             </div>
                             <div class="flex space-x-4 mt-2">
                                 <button type="button" id="tambah-pernyataan" class="text-sm text-green-500 font-semibold">+ Tambah Baris (Pernyataan)</button>
                                 <button type="button" id="tambah-kolom" class="text-sm text-blue-500 font-semibold">+ Tambah Kolom (Pilihan)</button>
                             </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Simpan Soal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tinymcePilihanConfig = {
            plugins: 'autolink lists link image charmap table',
            toolbar_mode: 'floating', height: 150, menubar: false,
             license_key: 'gpl',
            toolbar: 'undo redo | bold italic underline | bullist numlist | table',
            skin: 'oxide-dark', content_css: 'dark'
        };
        tinymce.init({
            selector: '#pertanyaan',
            plugins: 'autolink lists link image charmap table',
             license_key: 'gpl',
            toolbar_mode: 'floating', height: 300, skin: 'oxide-dark', content_css: 'dark',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | table'
        });

        const tipeSoalSelect = document.getElementById('tipe_soal');
        const formPgIsian = document.getElementById('form-pg-isian');
        const formKompleks = document.getElementById('form-kompleks');
        let pilihanCounter = 0;
        let pernyataanList = [{ teks: '' }];
        let kolomList = ['Benar', 'Salah'];

        function setupForm() {
            const tipe = tipeSoalSelect.value;
            formPgIsian.style.display = ['pilihan_ganda', 'pilihan_ganda_majemuk', 'isian'].includes(tipe) ? 'block' : 'none';
            formKompleks.style.display = tipe === 'pilihan_ganda_kompleks' ? 'block' : 'none';
            document.getElementById('opsi-jawaban-title').textContent = tipe === 'isian' ? 'Kunci Jawaban' : 'Opsi Jawaban';

            document.getElementById('pilihan-jawaban-container').innerHTML = '';
            pilihanCounter = 0;

            if (['pilihan_ganda', 'pilihan_ganda_majemuk'].includes(tipe)) { for(let i=0; i<4; i++) addPilihan(); }
            if (tipe === 'isian') { addPilihan(); }
            if (tipe === 'pilihan_ganda_kompleks') { renderMatriks(); }
        }

        function addPilihan() {
            const index = pilihanCounter++;
            const container = document.getElementById('pilihan-jawaban-container');
            const div = document.createElement('div');
            div.className = 'pilihan-item bg-gray-900/50 p-4 rounded-lg border border-gray-700';
            const tipe = tipeSoalSelect.value;

            if (tipe === 'isian') {
                div.innerHTML = `<label class="block font-medium text-sm text-gray-300">Jawaban Benar</label>
                                 <input type="text" name="pilihan[0][teks]" class="block mt-1 w-full bg-gray-900 border-gray-700 rounded-md">
                                 <input type="hidden" name="pilihan[0][benar]" value="1">`;
                container.innerHTML = '';
                container.appendChild(div);
                return;
            }

            const inputType = tipe === 'pilihan_ganda_majemuk' ? 'checkbox' : 'radio';
            const inputName = tipe === 'pilihan_ganda' ? 'jawaban_benar_radio' : `pilihan[${index}][benar]`;

            div.innerHTML = `
                <div class="flex items-start space-x-4">
                    <div class="flex-grow">
                        <textarea id="pilihan_teks_${index}" name="pilihan[${index}][teks]" class="hidden tinymce-pilihan"></textarea>
                    </div>
                    <div class="flex-shrink-0 flex items-center space-x-4 pt-2">
                        <div class="flex items-center">
                            <input type="hidden" name="pilihan[${index}][benar]" value="0">
                            <input type="${inputType}" name="${inputName}" value="${index}" class="pilihan-benar-input h-4 w-4 text-indigo-500 border-gray-600 rounded">
                            <label class="ml-2 text-sm text-gray-300">Benar</label>
                        </div>
                        <button type="button" class="remove-pilihan text-red-500 hover:text-red-400 text-2xl leading-none">&times;</button>
                    </div>
                </div>
                <div class="mt-3 flex items-center space-x-4">
                    <label for="pilihan_gambar_${index}" class="flex-shrink-0 cursor-pointer text-sm text-indigo-400 hover:text-indigo-300">Upload Gambar</label>
                    <input type="file" name="pilihan[${index}][gambar]" id="pilihan_gambar_${index}" class="pilihan-gambar-input hidden" accept="image/*">
                    <img id="pilihan_preview_${index}" class="h-10 w-16 object-contain rounded bg-gray-700 hidden" alt="Preview">
                </div>
            `;
            container.appendChild(div);
            tinymce.init({ selector: `#pilihan_teks_${index}`, ...tinymcePilihanConfig });
        }

        function renderMatriks() {
            const header = document.getElementById('matriks-kolom-header');
            const body = document.getElementById('matriks-body');
            header.innerHTML = `<tr><th class="p-2 text-left">Pernyataan</th>
                                ${kolomList.map((kolom, index) => `<th class="p-2 text-center relative">
                                <input type="text" name="kolom[${index}]" value="${kolom}" class="kolom-input w-28 bg-gray-700 text-center rounded p-1" data-index="${index}">
                                ${kolomList.length > 1 ? `<button type="button" class="remove-kolom absolute top-0 right-0 text-red-500" data-index="${index}">&times;</button>` : ''}
                                </th>`).join('')}
                                <th class="w-10"></th></tr>`;

            body.innerHTML = pernyataanList.map((p, pIndex) => `
                <tr class="pernyataan-row">
                    <td class="p-2"><textarea name="pernyataans[${pIndex}][teks]" class="pernyataan-input w-full bg-gray-700 rounded p-2" rows="2" data-index="${pIndex}">${p.teks}</textarea></td>
                    ${kolomList.map((_, kIndex) => `<td class="p-2 text-center align-middle">
                        <input type="radio" name="pernyataans[${pIndex}][jawaban_benar_radio]" value="${kIndex}" class="h-4 w-4 text-indigo-500">
                    </td>`).join('')}
                    <td class="p-2 text-center"><button type="button" class="remove-pernyataan text-red-500" data-index="${pIndex}">&times;</button></td>
                </tr>`).join('');
        }

        function handleImagePreview(fileInput, previewElement, textContainer) {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                    previewElement.classList.remove('hidden');
                    if (textContainer) textContainer.classList.add('hidden');
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
        }

        document.getElementById('tambah-pilihan-btn').addEventListener('click', addPilihan);
        document.getElementById('tambah-kolom').addEventListener('click', () => { kolomList.push('Kolom Baru'); renderMatriks(); });
        document.getElementById('tambah-pernyataan').addEventListener('click', () => { pernyataanList.push({ teks: '' }); renderMatriks(); });
        document.getElementById('gambar_soal').addEventListener('change', function() { handleImagePreview(this, document.getElementById('gambar-preview'), document.getElementById('gambar-text-container')); });
        tipeSoalSelect.addEventListener('change', setupForm);

        document.body.addEventListener('click', e => {
            if (e.target.classList.contains('remove-pilihan')) {
                const item = e.target.closest('.pilihan-item');
                const editorId = item.querySelector('textarea.tinymce-pilihan').id;
                tinymce.get(editorId).remove();
                item.remove();
            }
            if (e.target.classList.contains('remove-kolom')) { kolomList.splice(e.target.dataset.index, 1); renderMatriks(); }
            if (e.target.classList.contains('remove-pernyataan')) { pernyataanList.splice(e.target.dataset.index, 1); renderMatriks(); }
        });
        document.body.addEventListener('input', e => {
            if (e.target.classList.contains('kolom-input')) { kolomList[e.target.dataset.index] = e.target.value; }
            if (e.target.classList.contains('pernyataan-input')) { pernyataanList[e.target.dataset.index].teks = e.target.value; }
        });
         document.body.addEventListener('change', e => {
             if (e.target.matches('.pilihan-benar-input[type="checkbox"]')) {
                e.target.previousElementSibling.value = e.target.checked ? '1' : '0';
            }
            if (e.target.classList.contains('pilihan-gambar-input')) {
                const index = e.target.id.split('_').pop();
                const previewImg = document.getElementById(`pilihan_preview_${index}`);
                handleImagePreview(e.target, previewImg);
            }
        });

        setupForm();
    });
    </script>
    @endpush</x-app-layout>

