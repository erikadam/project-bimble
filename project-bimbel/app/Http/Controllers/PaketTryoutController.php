<?php

namespace App\Http\Controllers;

use App\Models\PaketTryout;
use App\Models\MataPelajaran;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\JawabanPeserta;
use App\Exports\LaporanSiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class PaketTryoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $jenjang = $request->get('jenjang');
        $paketTryouts = PaketTryout::when($jenjang, function ($query, $jenjang) {
            return $query->whereHas('mataPelajaran', function($query) use ($jenjang) {
                $query->where('jenjang_pendidikan', $jenjang);
            });
        })

        ->with('mataPelajaran')
        ->latest()
        ->get();
        $paketTryouts->each(function ($paket) {
            if ($paket->tipe_paket == 'event' && $paket->waktu_mulai) {
                $now = Carbon::now();
                $waktuMulai = Carbon::parse($paket->waktu_mulai);
                $waktuSelesai = $waktuMulai->copy()->addMinutes($paket->durasi_menit);

                $paket->waktu_mulai_timestamp = $waktuMulai->timestamp;
                $paket->waktu_selesai_timestamp = $waktuSelesai->timestamp;
                $paket->server_now_timestamp = $now->timestamp;

                if ($now->isBefore($waktuMulai)) {
                    $paket->event_status = 'Akan Datang';
                } elseif ($now->between($waktuMulai, $waktuSelesai)) {
                    $paket->event_status = 'Sedang Berlangsung';
                } else {
                    $paket->event_status = 'Telah Selesai';
                }
            }
        });

        return view('paket-tryout.index', compact('paketTryouts', 'jenjang'));
    }

   public function showAnalysis(PaketTryout $paketTryout)
    {
        $paketTryout->load('mataPelajaran');

        $allJawaban = JawabanPeserta::where('paket_tryout_id', $paketTryout->id)
                                    ->has('student')
                                    ->with(['student', 'soal.pilihanJawaban'])
                                    ->get();

        $soalIds = $paketTryout->mataPelajaran()->with('soal')->get()->pluck('soal.*.id')->flatten()->unique();
        $soals = Soal::whereIn('id', $soalIds)->with('pilihanJawaban', 'mataPelajaran')->get();

        $totalResponses = Student::where('paket_tryout_id', $paketTryout->id)->count();

        // MODIFIKASI: Perhitungan tingkat kesulitan soal
        $soals->each(function ($soal) use ($allJawaban, $totalResponses) {
            $jawabanForThisSoal = $allJawaban->where('soal_id', $soal->id);
            $totalCorrect = $jawabanForThisSoal->where('apakah_benar', true)->count();
            if ($totalResponses > 0) {
                $tingkatKesulitan = ($totalCorrect / $totalResponses) * 100;
                $soal->tingkat_kesulitan = round($tingkatKesulitan, 2);
                $soal->save(); // Simpan rating ke database
            } else {
                $soal->tingkat_kesulitan = null;
                $soal->save();
            }
        });


        $analysisDataByMapel = $soals->groupBy('mataPelajaran.nama_mapel')->map(function ($soalGroup) use ($allJawaban, $totalResponses) {

            return $soalGroup->map(function ($soal) use ($allJawaban, $totalResponses) {
                $jawabanForThisSoal = $allJawaban->where('soal_id', $soal->id);

                $studentsCorrect = $jawabanForThisSoal->where('apakah_benar', true)->pluck('student.nama_lengkap')->sort()->values();
                $studentsIncorrect = $jawabanForThisSoal->where('apakah_benar', false)->pluck('student.nama_lengkap')->sort()->values();

                $totalAnswered = $jawabanForThisSoal->filter(function($jawaban) {
                    return !empty($jawaban->jawaban_peserta) && $jawaban->jawaban_peserta !== '[]';
                })->count();

                $analysis = [
                    'pertanyaan' => $soal->pertanyaan,
                    'tingkat_kesulitan' => $soal->tingkat_kesulitan,
                    'pilihan' => collect(),
                    'students_correct' => $studentsCorrect,
                    'students_incorrect' => $studentsIncorrect,
                    'total_answered' => $totalAnswered,
                ];

                if ($soal->tipe_soal == 'pilihan_ganda' || $soal->tipe_soal == 'pilihan_ganda_majemuk') {
                    foreach ($soal->pilihanJawaban as $pilihan) {
                        $count = $jawabanForThisSoal->filter(function($jawaban) use ($pilihan) {
                            $jawabanPeserta = $jawaban->jawaban_peserta;
                            if (Str::startsWith($jawabanPeserta, '[') && Str::endsWith($jawabanPeserta, ']')) {
                                $decoded = json_decode($jawabanPeserta, true);
                                return is_array($decoded) && in_array($pilihan->pilihan_teks, $decoded);
                            }
                            return $jawabanPeserta === $pilihan->pilihan_teks;
                        })->count();

                        $analysis['pilihan']->push([
                            'teks' => $pilihan->pilihan_teks,
                            'count' => $count,
                            'is_correct' => $pilihan->apakah_benar,
                        ]);
                    }
                }

                return (object)$analysis;
            });
        });

        return view('paket-tryout.analysis', compact('paketTryout', 'analysisDataByMapel', 'totalResponses'));
    }
    public function create(Request $request)
    {
        $jenjang = $request->get('jenjang');
        $mataPelajaran = MataPelajaran::where('jenjang_pendidikan', $jenjang)->with('soal')->get();
        return view('paket-tryout.create', compact('mataPelajaran', 'jenjang'));
    }


    public function laporanIndex(Request $request)
    {
        $jenjang = $request->get('jenjang');
        $search = $request->get('search');

        $paketTryouts = PaketTryout::query()
            ->when($jenjang, function ($query, $jenjang) {
                return $query->whereHas('mataPelajaran', function($q) use ($jenjang) {
                    $q->where('jenjang_pendidikan', $jenjang);
                });
            })
            ->when($search, function ($query, $search) {
                return $query->where('kode_soal', 'like', '%' . $search . '%');
            })
            ->with('mataPelajaran')
            ->latest()
            ->get();

        return view('paket-tryout.laporan-index', compact('paketTryouts', 'jenjang', 'search'));
    }


    public function store(Request $request)
{
    // 1. Gabungkan semua aturan validasi menjadi satu array
    $rules = [
        'nama_paket' => 'required|string|max:255',
        'tipe_paket' => 'required|in:tryout,ulangan,event',
        'deskripsi' => 'nullable|string',
        'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
        'mata_pelajaran' => 'required|array|min:1',
        'mata_pelajaran.*.id' => 'required|exists:mata_pelajaran,id',
        'mata_pelajaran.*.durasi' => 'required|integer|min:1',
        'mata_pelajaran.*.urutan' => 'required|integer',
        'mata_pelajaran.*.soal' => 'required|array|min:1',
        'mata_pelajaran.*.soal.*' => 'exists:soal,id',
    ];

    // 2. Tambahkan aturan kondisional
    // Jika tipe BUKAN 'ulangan', maka min_wajib & max_opsional boleh diisi
    if ($request->input('tipe_paket') !== 'ulangan') {
        $rules['min_wajib'] = 'nullable|integer|min:0';
        $rules['max_opsional'] = 'nullable|integer|min:0';
    }

    // Jika tipe adalah 'event', maka waktu_mulai wajib diisi
    if ($request->input('tipe_paket') === 'event') {
        $rules['waktu_mulai'] = 'required|date';
    }

    // 3. Panggil validate HANYA SATU KALI
    $validatedData = $request->validate($rules);

    $totalDurasi = collect($request->mata_pelajaran)->sum('durasi');

    DB::transaction(function () use ($request, $validatedData, $totalDurasi) {
        $paketTryout = PaketTryout::create([
            'guru_id' => auth()->id(),
            'nama_paket' => $validatedData['nama_paket'],
            'tipe_paket' => $validatedData['tipe_paket'],
            'deskripsi' => $validatedData['deskripsi'],

            // Logika penyimpanan yang sudah diperbaiki
            'min_wajib' => $validatedData['min_wajib'] ?? null,
            'max_opsional' => $validatedData['max_opsional'] ?? null,

            'kode_soal' => strtoupper(Str::random(6)),
            'status' => 'published',
            'durasi_menit' => $totalDurasi,
            'waktu_mulai' => $validatedData['waktu_mulai'] ?? null,
        ]);

        $soalDipilihSyncData = [];
        $mapelSyncData = [];

        foreach ($request->mata_pelajaran as $mapelData) {
            $mapelSyncData[$mapelData['id']] = [
                'durasi_menit' => $mapelData['durasi'],
                'urutan' => $mapelData['urutan']
            ];

            foreach ($mapelData['soal'] as $soalId) {
                $soalDipilihSyncData[$soalId] = ['bobot' => 1]; // Default bobot 1
            }
        }

        $paketTryout->mataPelajaran()->sync($mapelSyncData);
        $paketTryout->soalPilihan()->sync($soalDipilihSyncData);
    });

    return redirect()->route('paket-tryout.index', ['jenjang' => $request->jenjang_pendidikan])->with('success', 'Paket Tryout berhasil dibuat.');
}


    public function showResponses(PaketTryout $paketTryout)
    {
        $paketTryout->load(['mataPelajaran', 'soalPilihan']);
        $students = Student::where('paket_tryout_id', $paketTryout->id)
                           ->with(['jawabanPeserta.soal.mataPelajaran', 'jawabanPeserta.soal.pilihanJawaban'])
                           ->get();

        $responseCount = $students->count();
        $semuaMapelPaket = $paketTryout->mataPelajaran;
        $bobotSoal = $paketTryout->soalPilihan->pluck('pivot.bobot', 'id');
        $totalBobotPaket = $bobotSoal->sum();
        $averageScore = 0;

        if ($responseCount > 0) {
            foreach ($students as $student) {
                $jawabanSiswa = $student->jawabanPeserta;
                $hasilPerMapel = [];

                foreach ($semuaMapelPaket as $mapel) {
                    $hasilPerMapel[$mapel->id] = [
                        'nama_mapel' => $mapel->nama_mapel,
                        'total_soal' => 0,
                        'total_benar' => 0,
                        'skor' => 0,
                        'dikerjakan' => false,
                        'detail_jawaban' => [],
                    ];
                }

                $jawabanPerMapel = $jawabanSiswa->groupBy('soal.mata_pelajaran_id');
                $totalBobotDiperoleh = 0;

                foreach ($jawabanPerMapel as $mapelId => $jawabanMapel) {
                    if (isset($hasilPerMapel[$mapelId])) {
                        $totalSoalMapel = 0;
                        $totalBenarMapel = 0;
                        $totalBobotMapel = 0;
                        $totalBobotDiperolehMapel = 0;

                        foreach ($jawabanMapel as $jawaban) {
                            $bobot = $bobotSoal[$jawaban->soal_id] ?? 1;
                            $totalSoalMapel++;
                            $totalBobotMapel += $bobot;
                            if ($jawaban->apakah_benar) {
                                $totalBenarMapel++;
                                $totalBobotDiperolehMapel += $bobot;
                            }
                        }

                        $totalBobotDiperoleh += $totalBobotDiperolehMapel;
                        $skorMapel = $totalBobotMapel > 0 ? ($totalBobotDiperolehMapel / $totalBobotMapel) * 100 : 0;

                        $detailJawaban = $jawabanMapel->map(function($item) use ($bobotSoal) {
                            return [
                                'pertanyaan' => $item->soal->pertanyaan,
                                'jawaban_peserta' => $item->jawaban_peserta,
                                'kunci_jawaban' => $item->soal->pilihanJawaban->where('apakah_benar', true)->pluck('pilihan_teks')->implode(', '),
                                'is_correct' => $item->apakah_benar,
                                'bobot' => $bobotSoal[$item->soal_id] ?? 1
                            ];
                        });

                        $hasilPerMapel[$mapelId] = [
                            'nama_mapel' => $jawabanMapel->first()->soal->mataPelajaran->nama_mapel,
                            'total_soal' => $totalSoalMapel,
                            'total_benar' => $totalBenarMapel,
                            'skor' => round($skorMapel, 2),
                            'dikerjakan' => true,
                            'detail_jawaban' => $detailJawaban
                        ];
                    }
                }

                // MODIFIKASI: Menambahkan data baru untuk ulangan
                if ($paketTryout->tipe_paket == 'ulangan') {
                    $student->total_benar = $jawabanSiswa->where('apakah_benar', true)->count();
                    $student->total_salah = $jawabanSiswa->where('apakah_benar', false)->count();
                    $student->total_soal = $jawabanSiswa->count();
                    $student->kelas = $student->kelas; // Menampilkan kelas di laporan
                    $student->asal_sekolah = $student->asal_sekolah; // Menampilkan asal sekolah
                }

                $skorTotal = $totalBobotPaket > 0 ? ($totalBobotDiperoleh / $totalBobotPaket) * 100 : 0;
                $student->skor_total = round($skorTotal, 2);
                $student->hasil_per_mapel = $hasilPerMapel;
            }
            $averageScore = $students->avg('skor_total');
        }

        return view('paket-tryout.responses', compact('paketTryout', 'responseCount', 'averageScore', 'students', 'semuaMapelPaket'));
    }
    public function show(PaketTryout $paketTryout)
    {
        // --- PERBAIKAN DI SINI ---
        // Mengubah 'pivot_urutan' menjadi 'urutan' yang merupakan nama kolom asli di tabel pivot.
        $paketTryout->load(['mataPelajaran' => function ($query) {
            $query->orderBy('paket_mapel.urutan', 'asc');
        }, 'soalPilihan.mataPelajaran']);

        // Kelompokkan soal yang dipilih berdasarkan mata pelajaran
        $soalPerMapel = $paketTryout->soalPilihan->groupBy('mata_pelajaran_id');

        $totalDurasi = $paketTryout->mataPelajaran->sum('pivot.durasi_menit');
            if ($paketTryout->tipe_paket == 'event' && $paketTryout->waktu_mulai) {
            $now = Carbon::now();
            $waktuMulai = Carbon::parse($paketTryout->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($paketTryout->durasi_menit);

            $paketTryout->waktu_mulai_timestamp = $waktuMulai->timestamp;
            $paketTryout->waktu_selesai_timestamp = $waktuSelesai->timestamp;
            $paketTryout->server_now_timestamp = $now->timestamp;

            if ($now->isBefore($waktuMulai)) {
                $paketTryout->event_status = 'Akan Datang';
            } elseif ($now->between($waktuMulai, $waktuSelesai)) {
                $paketTryout->event_status = 'Sedang Berlangsung';
            } else {
                $paketTryout->event_status = 'Telah Selesai';
            }
        }
        return view('paket-tryout.show', compact('paketTryout', 'totalDurasi', 'soalPerMapel'));
    }
    public function edit(PaketTryout $paketTryout)
    {
        $paketTryout->load(['mataPelajaran', 'soalPilihan']);
        $jenjang = $paketTryout->mataPelajaran->first()->jenjang_pendidikan;
        $mataPelajaranOptions = MataPelajaran::where('jenjang_pendidikan', $jenjang)->with('soal')->get();
        $selectedSoalIds = $paketTryout->soalPilihan->pluck('id')->toArray();
        return view('paket-tryout.edit', compact('paketTryout', 'mataPelajaranOptions', 'jenjang', 'selectedSoalIds'));
    }

    public function update(Request $request, PaketTryout $paketTryout)
{
    // 1. Gabungkan semua aturan validasi menjadi satu array
    $rules = [
        'nama_paket' => 'required|string|max:255',
        'tipe_paket' => 'required|in:tryout,ulangan,event',
        'deskripsi' => 'nullable|string',
        'mata_pelajaran' => 'required|array|min:1',
        'mata_pelajaran.*.id' => 'required|exists:mata_pelajaran,id',
        'mata_pelajaran.*.durasi' => 'required|integer|min:1',
        'mata_pelajaran.*.urutan' => 'required|integer',
        'mata_pelajaran.*.soal' => 'required|array|min:1',
        'mata_pelajaran.*.soal.*' => 'exists:soal,id',
    ];

    // 2. Tambahkan aturan kondisional
    if ($request->input('tipe_paket') !== 'ulangan') {
        $rules['min_wajib'] = 'nullable|integer|min:0';
        $rules['max_opsional'] = 'nullable|integer|min:0';
    }

    if ($request->input('tipe_paket') === 'event') {
        $rules['waktu_mulai'] = 'required|date';
    }

    // 3. Panggil validate HANYA SATU KALI
    $validatedData = $request->validate($rules);

    $totalDurasi = collect($validatedData['mata_pelajaran'])->sum('durasi');

    DB::transaction(function () use ($request, $paketTryout, $validatedData, $totalDurasi) {
        $paketTryout->update([
            'nama_paket' => $validatedData['nama_paket'],
            'tipe_paket' => $validatedData['tipe_paket'],
            'deskripsi' => $validatedData['deskripsi'],

            // Logika penyimpanan yang sudah diperbaiki
            'min_wajib' => $validatedData['min_wajib'] ?? null,
            'max_opsional' => $validatedData['max_opsional'] ?? null,

            'durasi_menit' => $totalDurasi,
            'waktu_mulai' => $validatedData['waktu_mulai'] ?? null,
        ]);

        $soalDipilihSyncData = [];
        $mapelSyncData = [];

        $existingBobots = $paketTryout->soalPilihan->pluck('pivot.bobot', 'id');

        foreach ($request->mata_pelajaran as $mapelData) {
            $mapelSyncData[$mapelData['id']] = [
                'durasi_menit' => $mapelData['durasi'],
                'urutan' => $mapelData['urutan'],
            ];
            if (!empty($mapelData['soal'])) {
                foreach($mapelData['soal'] as $soalId) {
                    $soalDipilihSyncData[$soalId] = ['bobot' => $existingBobots[$soalId] ?? 1];
                }
            }
        }

        // --- INI ADALAH BARIS YANG DIPERBAIKI ---
        $paketTryout->mataPelajaran()->sync($mapelSyncData);
        // -----------------------------------------

        $paketTryout->soalPilihan()->sync($soalDipilihSyncData);
    });

    return redirect()->route('paket-tryout.show', $paketTryout)->with('success', 'Paket Tryout berhasil diperbarui.');
}
    public function destroy(PaketTryout $paketTryout)
    {
        $jenjang = $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? null;
        $paketTryout->delete();
        return redirect()->route('paket-tryout.index', ['jenjang' => $jenjang])->with('success', 'Paket Tryout berhasil dihapus.');
    }

    public function toggleStatus(PaketTryout $paketTryout)
    {
        if ($paketTryout->tipe_paket == 'event' && $paketTryout->status == 'draft') {
            if ($paketTryout->waktu_mulai) {
                $waktuSelesai = Carbon::parse($paketTryout->waktu_mulai)->addMinutes($paketTryout->durasi_menit);
                if ($waktuSelesai->isPast()) {
                    return redirect()->back()->with('error', 'Waktu event sudah berakhir. Silakan edit paket dan atur jadwal baru untuk mempublikasikannya kembali.');
                }
            }
        }

        $paketTryout->status = $paketTryout->status == 'published' ? 'draft' : 'published';
        $paketTryout->save();

        $message = $paketTryout->status == 'published' ? 'Paket Tryout berhasil dipublikasikan.' : 'Paket Tryout berhasil diarsipkan (draft).';

        return redirect()->back()->with('success', $message);
    }

    public function demoIndex()
    {
        $paketTryouts = PaketTryout::latest()->get();
        return view('paket-tryout.demo-index', compact('paketTryouts'));
    }

    public function review(PaketTryout $paketTryout)
    {
        $paketTryout->load(['mataPelajaran.soal' => function ($query) {
            $query->where('status', 'aktif')->with('pilihanJawaban');
        }]);

        return view('paket-tryout.review', compact('paketTryout'));
    }

    public function exportLaporanSiswa(PaketTryout $paketTryout)
{
    // 1. Ambil daftar mata pelajaran dari paket ini untuk dijadikan header kolom
    $mataPelajaran = $paketTryout->mataPelajaran()->orderBy('urutan', 'asc')->get();

    // 2. Ambil siswa yang valid (punya nama) dan muat semua relasi yang dibutuhkan
    //    (jawaban, soal, dan mapel dari soal) untuk menghindari query berulang.
    $students = $paketTryout->students()
                            ->whereNotNull('nama_lengkap')
                            ->where('nama_lengkap', '!=', '')
                            ->with('jawabanPeserta.soal.mataPelajaran')
                            ->get();

    $namaFile = 'laporan-' . Str::slug($paketTryout->nama_paket) . '.xlsx';

    // 3. Kirimkan DUA variabel (siswa dan daftar mapel) ke kelas Export
    return Excel::download(new LaporanSiswaExport($students, $mataPelajaran), $namaFile);
}
    public function getBobotSoal(PaketTryout $paketTryout)
    {
        $soal = $paketTryout->soalPilihan()->with('mataPelajaran')->get()
            ->sortBy('mataPelajaran.nama_mapel')
            ->groupBy('mataPelajaran.nama_mapel');

        return response()->json($soal);
    }

    public function saveBobotSoal(Request $request, PaketTryout $paketTryout)
    {
        $validated = $request->validate([
            'bobots' => 'required|array',
            'bobots.*.soal_id' => 'required|exists:soal,id',
            'bobots.*.bobot' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $paketTryout) {
            foreach ($validated['bobots'] as $data) {
                $paketTryout->soalPilihan()->updateExistingPivot($data['soal_id'], [
                    'bobot' => $data['bobot']
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Bobot nilai berhasil diperbarui.']);
    }
}
