<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('siswa.ujian.mulai_pengerjaan', $paketTryout->id) }}">
                        @csrf
                        <h3 class="text-xl font-bold mb-4">Pilih Mata Pelajaran untuk Ujian</h3>
                        <p class="text-gray-600 mb-6">
                            Anda wajib mengerjakan minimal <strong class="text-gray-800">{{ $paketTryout->min_wajib ?? 0 }}</strong> mata pelajaran wajib, dan dapat memilih maksimal <strong class="text-gray-800">{{ $paketTryout->max_opsional ?? 0 }}</strong> mata pelajaran opsional.
                        </p>

                        <div class="space-y-4" x-data="{
                            selectedWajib: [],
                            selectedOpsional: [],
                            minWajib: {{ $paketTryout->min_wajib ?? 0 }},
                            maxOpsional: {{ $paketTryout->max_opsional ?? 0 }},

                            init() {
                                // Secara otomatis memilih semua mapel wajib saat halaman dibuka
                                this.selectedWajib = Array.from(this.$root.querySelectorAll('input[name^=mata_pelajaran_wajib]')).map(el => el.value);
                                this.selectedOpsional = Array.from(this.$root.querySelectorAll('input[name^=mata_pelajaran_opsional]:checked')).map(el => el.value);
                            },

                            toggleWajib(event) {
                                const value = event.target.value;
                                if (event.target.checked) {
                                    this.selectedWajib.push(value);
                                } else {
                                    // Hanya izinkan uncheck jika jumlahnya masih di atas atau sama dengan minimal
                                    if (this.selectedWajib.length > this.minWajib) {
                                        const index = this.selectedWajib.indexOf(value);
                                        if (index > -1) {
                                            this.selectedWajib.splice(index, 1);
                                        }
                                    } else {
                                        // Batalkan aksi uncheck
                                        event.target.checked = true;
                                    }
                                }
                            },

                            toggleOpsional(event) {
                                const value = event.target.value;
                                if (event.target.checked) {
                                    this.selectedOpsional.push(value);
                                    if (this.selectedOpsional.length > this.maxOpsional) {
                                        // Hapus pilihan pertama (paling lama) jika melebihi batas
                                        this.selectedOpsional.shift();
                                    }
                                } else {
                                    const index = this.selectedOpsional.indexOf(value);
                                    if (index > -1) {
                                        this.selectedOpsional.splice(index, 1);
                                    }
                                }
                            }
                        }">
                            @if($mataPelajaran->where('is_wajib', true)->isNotEmpty())
                                <h4 class="font-semibold text-gray-800 mt-6 border-b pb-2">Mata Pelajaran Wajib</h4>
                                @foreach ($mataPelajaran->where('is_wajib', true) as $mapel)
                                    <label class="block p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input
                                            type="checkbox"
                                            name="mata_pelajaran_wajib[]"
                                            value="{{ $mapel->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            x-on:change="toggleWajib($event)"
                                            :checked="selectedWajib.includes('{{ $mapel->id }}')"
                                        >
                                        <span class="ml-3 text-gray-800">{{ $mapel->nama_mapel }}</span>
                                    </label>
                                @endforeach
                            @endif

                            @if($mataPelajaran->where('is_wajib', false)->isNotEmpty())
                                <h4 class="font-semibold text-gray-800 mt-6 border-b pb-2">Mata Pelajaran Opsional</h4>
                                @foreach ($mataPelajaran->where('is_wajib', false) as $mapel)
                                    <label class="block p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input
                                            type="checkbox"
                                            name="mata_pelajaran_opsional[]"
                                            value="{{ $mapel->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            x-on:change="toggleOpsional($event)"
                                            :checked="selectedOpsional.includes('{{ $mapel->id }}')"
                                        >
                                        <span class="ml-3 text-gray-800">{{ $mapel->nama_mapel }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-primary-button x-bind:disabled="selectedWajib.length < minWajib || selectedOpsional.length > maxOpsional">
                                {{ __('Mulai Pengerjaan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
