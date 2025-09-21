{{-- resources/views/ulangan/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Kelola Soal Ulangan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $ulangan->nama_ulangan }} - {{ $ulangan->mataPelajaran->nama_mapel }}
                </p>
            </div>
            <div class="flex items-center space-x-4 mt-4 sm:mt-0">
                <a href="{{ route('ulangan.laporan.responses', $ulangan->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold text-xs uppercase tracking-widest rounded-md transition">
                    Lihat Laporan
                </a>
                <form action="{{ route('ulangan.toggleStatus', $ulangan) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 {{ $ulangan->status == 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white font-semibold text-xs uppercase tracking-widest rounded-md transition">
                        {{ $ulangan->status == 'published' ? 'Ubah ke Draft' : 'Publikasikan' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            <div id="notification" class="fixed top-20 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg z-50 transition-transform transform translate-x-full" style="display: none;">
                <p id="notification-message"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom 1: Soal Terpilih --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Soal di Ulangan Ini (<span id="jumlah-terpilih">{{ $ulangan->soal->count() }}</span>)</h3>
                        <div id="soal-terpilih-container" class="space-y-3">
                            @foreach ($ulangan->soal as $soal)
                                @include('ulangan.partials.soal-card', ['soal' => $soal, 'action' => 'remove'])
                            @endforeach
                        </div>
                        {{-- PESAN KOSONG DIPINDAHKAN KE LUAR LOOP --}}
                        <p id="empty-terpilih" class="text-gray-500 dark:text-gray-400 mt-4" style="display: none;">Belum ada soal yang ditambahkan.</p>
                    </div>
                </div>

                {{-- Kolom 2: Bank Soal --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Bank Soal: {{ $ulangan->mataPelajaran->nama_mapel }}</h3>
                         <div id="soal-tersedia-container" class="space-y-3">
                             @foreach ($soalTersedia as $soal)
                                 @include('ulangan.partials.soal-card', ['soal' => $soal, 'action' => 'add'])
                             @endforeach
                         </div>
                         {{-- PESAN KOSONG DIPINDAHKAN KE LUAR LOOP --}}
                         <p id="empty-tersedia" class="text-gray-500 dark:text-gray-400 mt-4" style="display: none;">Semua soal sudah ditambahkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showNotification(message, isSuccess = true) {
                const notification = document.getElementById('notification');
                const messageElement = document.getElementById('notification-message');
                messageElement.textContent = message;
                notification.style.backgroundColor = isSuccess ? '#48bb78' : '#f56565';
                notification.style.display = 'block';
                notification.classList.remove('translate-x-full');
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => { notification.style.display = 'none'; }, 300);
                }, 3000);
            }

            function updateUI() {
                const terpilihContainer = document.getElementById('soal-terpilih-container');
                const tersediaContainer = document.getElementById('soal-tersedia-container');
                const jumlahTerpilih = terpilihContainer.querySelectorAll('.soal-card').length;
                document.getElementById('jumlah-terpilih').textContent = jumlahTerpilih;
                document.getElementById('empty-terpilih').style.display = jumlahTerpilih === 0 ? 'block' : 'none';
                document.getElementById('empty-tersedia').style.display = tersediaContainer.querySelectorAll('.soal-card').length === 0 ? 'block' : 'none';
            }

            async function handleSoalAction(e) {
                const button = e.target.closest('.add-soal-btn, .remove-soal-btn');
                if (!button) { return; }
                e.preventDefault();
                button.disabled = true;

                const soalId = button.dataset.soalId;
                const isAdding = button.classList.contains('add-soal-btn');
                const url = isAdding ? `{{ route('ulangan.addSoal', $ulangan) }}` : `{{ route('ulangan.removeSoal', $ulangan) }}`;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ soal_id: soalId })
                    });
                    if (!response.ok) { throw new Error(`Server error: ${response.statusText}`); }
                    const result = await response.json();
                    if (result.success) {
                        showNotification(result.message);
                        const card = button.closest('.soal-card');
                        if (isAdding) {
                            document.getElementById('soal-terpilih-container').appendChild(card);
                            button.textContent = 'Hapus';
                            button.classList.replace('add-soal-btn', 'remove-soal-btn');
                            button.classList.replace('bg-blue-500', 'bg-red-500');
                            button.classList.replace('hover:bg-blue-600', 'hover:bg-red-600');
                        } else {
                            document.getElementById('soal-tersedia-container').appendChild(card);
                            button.textContent = 'Tambah';
                            button.classList.replace('remove-soal-btn', 'add-soal-btn');
                            button.classList.replace('bg-red-500', 'bg-blue-500');
                            button.classList.replace('hover:bg-red-600', 'hover:bg-blue-600');
                        }
                        updateUI();
                    } else {
                        showNotification(result.message || 'Gagal memproses permintaan.', false);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan. Cek console browser untuk detail.', false);
                } finally {
                    button.disabled = false;
                }
            }
            document.body.addEventListener('click', handleSoalAction);
            updateUI();
        });
    </script>
    @endpush
</x-app-layout>
