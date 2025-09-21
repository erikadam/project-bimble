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

                        <div class="mb-4">
                            <x-input-label for="pertanyaan" :value="__('Teks Pertanyaan')" class="mb-2"/>
                            <textarea id="pertanyaan" name="pertanyaan" class="hidden">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                        </div>

                        <div class="mb-4">
                             <label class="block font-medium text-sm text-gray-300">Gambar Soal (Opsional)</label>
                             @if($soal->gambar_path)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($soal->gambar_path) }}" class="max-h-40 rounded-md">
                                    <p class="text-xs text-gray-400 mt-1">Gambar saat ini. Upload file baru untuk menggantinya.</p>
                                </div>
                             @endif
                             <input type="file" name="gambar_soal" class="mt-2 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-500 file:text-white hover:file:bg-indigo-600">
                        </div>

                        <div class="mt-6">
                            <x-input-label for="tipe_soal" :value="__('Tipe Soal')" />
                            <select id="tipe_soal" name="tipe_soal" class="block w-full mt-1 bg-gray-900 border-gray-700 rounded-md">
                                <option value="pilihan_ganda_tunggal" @selected(old('tipe_soal', $soal->tipe_soal) == 'pilihan_ganda_tunggal')>Pilihan Ganda</option>
                                <option value="pilihan_ganda_majemuk" @selected(old('tipe_soal', $soal->tipe_soal) == 'pilihan_ganda_majemuk')>Pilihan Ganda Majemuk</option>
                                <option value="isian" @selected(old('tipe_soal', $soal->tipe_soal) == 'isian')>Isian Singkat</option>
                                <option value="pilihan_ganda_kompleks" @selected(old('tipe_soal', $soal->tipe_soal) == 'pilihan_ganda_kompleks')>Pilihan Ganda Kompleks (Matriks)</option>
                            </select>
                        </div>

                        <hr class="my-6 border-gray-700">

                        <div id="form-pg-isian" class="space-y-4" style="display: none;">
                           <h3 class="text-lg font-medium text-gray-300" id="opsi-jawaban-title"></h3>
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
                                 <button type="button" id="tambah-pernyataan" class="text-sm text-green-500 font-semibold">+ Tambah Baris</button>
                                 <button type="button" id="tambah-kolom" class="text-sm text-blue-500 font-semibold">+ Tambah Kolom</button>
                             </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-700 space-x-4">
                            <a href="{{ route('mata-pelajaran.soal.index', $soal->mata_pelajaran_id) }}" class="text-gray-400 hover:text-gray-200 font-medium">Batalkan</a>
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const soalData = @json($soal);
        const mataPelajaranId = soalData.mata_pelajaran_id;

        const tinymcePilihanConfig = {
            plugins: 'autolink lists link image charmap table',
            toolbar_mode: 'floating', height: 150, menubar: false,
            toolbar: 'undo redo | bold italic underline | bullist numlist | table',
            skin: 'oxide-dark', content_css: 'dark'
        };
        tinymce.init({
            selector: '#pertanyaan',
            plugins: 'autolink lists link image charmap table',
            toolbar_mode: 'floating', height: 300, skin: 'oxide-dark', content_css: 'dark',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | table'
        });

        const tipeSoalSelect = document.getElementById('tipe_soal');
        const formPgIsian = document.getElementById('form-pg-isian');
        const formKompleks = document.getElementById('form-kompleks');
        let pilihanCounter = 0;
        let pernyataanList = [];
        let kolomList = [];

        function setupForm() {
            const tipe = tipeSoalSelect.value;
            formPgIsian.style.display = ['pilihan_ganda_tunggal', 'pilihan_ganda_majemuk', 'isian'].includes(tipe) ? 'block' : 'none';
            formKompleks.style.display = tipe === 'pilihan_ganda_kompleks' ? 'block' : 'none';
            document.getElementById('opsi-jawaban-title').textContent = tipe === 'isian' ? 'Kunci Jawaban' : 'Opsi Jawaban';
        }

        function addPilihan(data = {}) {
            const index = pilihanCounter++;
            const container = document.getElementById('pilihan-jawaban-container');
            const div = document.createElement('div');
            div.className = 'pilihan-item bg-gray-900/50 p-4 rounded-lg border border-gray-700';
            const tipe = tipeSoalSelect.value;

            if (tipe === 'isian') {
                div.innerHTML = `<label class="block font-medium text-sm text-gray-300">Jawaban Benar</label>
                                 <input type="hidden" name="pilihan[0][id]" value="${data.id || ''}">
                                 <input type="text" name="pilihan[0][teks]" value="${data.pilihan_teks || ''}" class="block mt-1 w-full bg-gray-900 border-gray-700 rounded-md">
                                 <input type="hidden" name="pilihan[0][benar]" value="1">`;
                container.innerHTML = '';
                container.appendChild(div);
                return;
            }

            const inputType = tipe === 'pilihan_ganda_majemuk' ? 'checkbox' : 'radio';
            const inputName = tipe === 'pilihan_ganda_tunggal' ? 'jawaban_benar_radio' : `pilihan[${index}][benar]`;
            const isChecked = data.apakah_benar ? 'checked' : '';
            const value = tipe === 'pilihan_ganda_tunggal' ? index : '1';
            const showBenarLabel = tipe !== 'isian';
            const showCheckbox = tipe !== 'isian';
            const hiddenValue = tipe === 'pilihan_ganda_majemuk' ? (data.apakah_benar ? '1' : '0') : '';

            div.innerHTML = `
                <input type="hidden" name="pilihan[${index}][id]" value="${data.id || ''}">
                <div class="flex items-start space-x-4">
                    <div class="flex-grow">
                        <textarea id="pilihan_teks_${index}" name="pilihan[${index}][teks]" class="hidden tinymce-pilihan">${data.pilihan_teks || ''}</textarea>
                    </div>
                    <div class="flex-shrink-0 flex items-center space-x-4 pt-2">
                        <div class="flex items-center">
                            ${showCheckbox ? `<input type="${inputType}" name="${inputName}" value="${value}" class="pilihan-benar-input h-4 w-4 text-indigo-500" ${isChecked}>` : ''}
                            ${showBenarLabel ? `<label class="ml-2 text-sm text-gray-300">Benar</label>` : ''}
                        </div>
                        <button type="button" class="remove-pilihan text-red-500 hover:text-red-400 text-2xl leading-none">&times;</button>
                    </div>
                </div>
                <div class="mt-3 flex items-center space-x-4">
                    <label for="pilihan_gambar_${index}" class="flex-shrink-0 cursor-pointer text-sm text-indigo-400">Upload Gambar</label>
                    <input type="file" name="pilihan[${index}][gambar]" id="pilihan_gambar_${index}" class="pilihan-gambar-input hidden" accept="image/*">
                    <img id="pilihan_preview_${index}" src="${data.gambar_path ? '/storage/' + data.gambar_path : ''}" class="h-10 w-16 object-contain rounded bg-gray-700 ${data.gambar_path ? '' : 'hidden'}">
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
                    <input type="hidden" name="pernyataans[${pIndex}][id]" value="${p.id || ''}">
                    <td class="p-2"><textarea name="pernyataans[${pIndex}][teks]" class="pernyataan-input w-full bg-gray-700 rounded p-2" rows="2" data-index="${pIndex}">${p.teks || ''}</textarea></td>
                    ${kolomList.map((_, kIndex) => `<td class="p-2 text-center align-middle">
                        <input type="radio" name="pernyataans[${pIndex}][jawaban_benar_radio]" value="${kIndex}" class="h-4 w-4 text-indigo-500" ${p.jawaban == kIndex ? 'checked' : ''}>
                    </td>`).join('')}
                    <td class="p-2 text-center"><button type="button" class="remove-pernyataan text-red-500" data-index="${pIndex}">&times;</button></td>
                </tr>`).join('');
        }

        function handleImagePreview(fileInput, previewElement) {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewElement.src = e.target.result;
                    previewElement.classList.remove('hidden');
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        }

        function initializeFormWithData() {
            const tipe = soalData.tipe_soal;
            tipeSoalSelect.value = tipe;

            if (['pilihan_ganda_tunggal', 'pilihan_ganda_majemuk', 'isian'].includes(tipe)) {
                if (soalData.pilihan_jawaban && soalData.pilihan_jawaban.length > 0) {
                    soalData.pilihan_jawaban.forEach(p => addPilihan(p));
                } else { addPilihan(); }
            } else if (tipe === 'pilihan_ganda_kompleks') {
                try {
                    kolomList = JSON.parse(soalData.pilihan_kompleks);
                } catch (e) {
                    kolomList = ['Benar', 'Salah'];
                }

                pernyataanList = soalData.pernyataans.map(p => {
                    const jawabanIndex = p.pilihan_jawabans.findIndex(pj => pj.apakah_benar);
                    return {
                        id: p.id,
                        teks: p.pernyataan_teks,
                        jawaban: jawabanIndex
                    };
                });

                if (pernyataanList.length === 0) {
                     pernyataanList.push({ teks: '' });
                }

                renderMatriks();
            }
            setupForm();
        }

        document.getElementById('tambah-pilihan-btn').addEventListener('click', () => addPilihan());
        document.getElementById('tambah-kolom').addEventListener('click', () => { kolomList.push('Baru'); renderMatriks(); });
        document.getElementById('tambah-pernyataan').addEventListener('click', () => { pernyataanList.push({ teks: '' }); renderMatriks(); });
        tipeSoalSelect.addEventListener('change', () => {
             setupForm();
             pilihanCounter = 0; // Reset counter
             document.getElementById('pilihan-jawaban-container').innerHTML = ''; // Clear previous options
             addPilihan();
        });

        document.body.addEventListener('click', e => {
            if (e.target.classList.contains('remove-pilihan')) { e.target.closest('.pilihan-item').remove(); }
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
                handleImagePreview(e.target, document.getElementById(`pilihan_preview_${index}`));
            }
        });

        initializeFormWithData();
    });
    </script>
    @endpush
</x-app-layout>
