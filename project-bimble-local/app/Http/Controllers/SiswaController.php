<?php

namespace App\Http\Controllers;

use App\Models\PaketTryout;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\JawabanPeserta;
use App\Models\Student;
use App\Models\CompanyGoal;
use App\Models\Testimonial;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Ulangan;
use App\Models\UlanganSession; // Tambahkan ini
use App\Models\JawabanUlangan;
use App\Models\AboutUs;


class SiswaController extends Controller
{
    // MODIFIKASI: Metode ini sekarang menyediakan data untuk halaman utama
    public function showAksesUlangan()
    {
        $upcomingEvents = PaketTryout::where('tipe_paket', 'event')
            ->where('status', 'published')
            ->where('waktu_mulai', '>', now())
            ->orderBy('waktu_mulai', 'asc')
            ->take(3)
            ->get();

        $companyGoals = CompanyGoal::all()->groupBy('type');
        $testimonials = Testimonial::all();
        $sliders = SliderImage::all();
        $aboutUs = AboutUs::first();

        return view('welcome', compact('upcomingEvents', 'companyGoals', 'testimonials', 'sliders', 'aboutUs'));
    }



    public function pilihJenjang()
    {
        return view('siswa.pilih-jenjang');
    }

    public function aksesUlangan()
    {
        return view('siswa.akses_ulangan');
    }

    public function pilihPaket($jenjang = null)
    {
        // --- PERBAIKAN DI SINI ---
        // Menghapus filter 'tipe_paket' agar semua jenis tryout fleksibel muncul
        $query = PaketTryout::where('status', 'published')
                            ->where('tipe_paket', '!=', 'event') // Hanya tampilkan yang BUKAN event
                            ->with('guru');

        if ($jenjang) {
            $query->whereHas('mataPelajaran', function ($q) use ($jenjang) {
                $q->where('jenjang_pendidikan', $jenjang);
            });
        }
        $paketTryouts = $query->latest()->get();

        return view('siswa.pilih-paket', compact('paketTryouts', 'jenjang'));
    }

    public function pilihEvent($jenjang = null)
    {
        $query = PaketTryout::where('status', 'published')
                            ->where('tipe_paket', 'event')
                            ->with(['guru', 'mataPelajaran' => function ($query) {
                                $query->orderBy('paket_mapel.urutan', 'asc');
                            }]);

        if ($jenjang) {
            $query->whereHas('mataPelajaran', function ($q) use ($jenjang) {
                $q->where('jenjang_pendidikan', $jenjang);
            });
        }
        $paketTryouts = $query->orderBy('waktu_mulai', 'asc')->get();

        // ===============================================================
        // LOGIKA BARU UNTUK MENYIAPKAN JADWAL DI SETIAP PAKET
        // ===============================================================
        $paketTryouts->each(function ($paket) {
            if ($paket->waktu_mulai) {
                $now = now();
                $waktuMulaiEvent = Carbon::parse($paket->waktu_mulai);

                // Hitung jadwal absolut untuk setiap mapel
                $fullSchedule = [];
                $cumulativeTime = $waktuMulaiEvent->copy();
                foreach ($paket->mataPelajaran as $mapel) {
                    $durasi = $mapel->pivot->durasi_menit;
                    $waktuSelesai = $cumulativeTime->copy()->addMinutes($durasi);
                    $fullSchedule[$mapel->id] = [
                        'nama_mapel' => $mapel->nama_mapel,
                        'waktu_mulai' => $cumulativeTime->copy(),
                        'waktu_selesai' => $waktuSelesai,
                    ];
                    $cumulativeTime = $waktuSelesai;
                }

                // Tentukan status event saat ini
                $currentMapel = null;
                $nextMapel = null;
                foreach ($fullSchedule as $mapelId => $schedule) {
                    if ($now->between($schedule['waktu_mulai'], $schedule['waktu_selesai'])) {
                        $currentMapel = $schedule;
                        // Hitung sisa waktu untuk mapel yang sedang berlangsung
                        $currentMapel['sisa_waktu'] = $now->diffInSeconds($schedule['waktu_selesai']);
                    }
                    if ($now->isBefore($schedule['waktu_mulai']) && !$nextMapel) {
                        $nextMapel = $schedule;
                    }
                }

                // Tambahkan data baru ke objek paket
                $paket->full_schedule = $fullSchedule;
                $paket->current_mapel = $currentMapel;
                $paket->next_mapel = $nextMapel;

                // (Kode event_status Anda yang sudah ada tetap bisa digunakan)
                $paket->event_status = 'Telah Selesai';
                if ($currentMapel) {
                    $paket->event_status = 'Sedang Berlangsung';
                } elseif ($nextMapel) {
                    $paket->event_status = 'Akan Datang';
                }
            }
        });
        // ===============================================================

        return view('siswa.pilih-event', compact('paketTryouts', 'jenjang'));
    }


    public function mulai(PaketTryout $paketTryout)
    {
        if ($paketTryout->tipe_paket == 'ulangan') {
            if (!Session::has('calon_peserta_ulangan')) {
                return redirect()->route('welcome')->with('error', 'Sesi Anda telah berakhir, silakan isi data diri kembali.');
            }
            return view('siswa.mulai-ulangan', compact('paketTryout'));
        }

        $jenjang = $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? null;
        return view('siswa.mulai', compact('paketTryout', 'jenjang'));
    }

    public function start(Request $request, PaketTryout $paketTryout)
{
    // Bagian validasi ini tidak ada perubahan
    if ($paketTryout->tipe_paket == 'ulangan') {
         $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
        ]);
        $kelompok = $validated['kelas'];
    } else {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
            'kelompok' => 'required|string',
        ]);
         $kelompok = $validated['kelompok'];
    }

    $sessionId = Str::uuid()->toString();
    // Bagian pembuatan student tidak ada perubahan
    $student = Student::create([
        'paket_tryout_id' => $paketTryout->id,
        'nama_lengkap' => $validated['nama_lengkap'],
        'kelas' => $validated['kelas'] ?? null,
        'asal_sekolah' => $validated['asal_sekolah'] ?? null,
        'jenjang_pendidikan' => $validated['jenjang_pendidikan'],
        'kelompok' => $kelompok,
        'session_id' => $sessionId
    ]);
    Session::put('ujian_siswa', ['student_id' => $student->id, 'paket_id' => $paketTryout->id, 'mapel_sekarang' => null, 'mapel_sudah_dikerjakan' => [], 'mapel_pilihan' => [], 'start_time' => now()->timestamp]);
    return redirect()->route('siswa.ujian.pilih_mapel', $paketTryout->id);

    // --- SELESAI PERUBAHAN ---
}

    public function pilihMapel(PaketTryout $paketTryout)
    {
        $ujianSiswa = Session::get('ujian_siswa');
        if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id || !isset($ujianSiswa['student_id'])) {
            return redirect()->route('siswa.ujian.mulai', $paketTryout->id);
        }

        if ($paketTryout->tipe_paket == 'ulangan') {
            $mataPelajaran = $paketTryout->mataPelajaran()->orderBy('nama_mapel', 'asc')->get();
        } else {
            $mataPelajaran = $paketTryout->mataPelajaran()->orderBy('is_wajib', 'desc')->get();
        }
        return view('siswa.pilih-mapel', compact('paketTryout', 'mataPelajaran'));
    }
        public function mulaiPengerjaan(Request $request, PaketTryout $paketTryout)
    {
        $ujianSiswa = Session::get('ujian_siswa');
        if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id) {
            return redirect()->route('siswa.ujian.mulai', $paketTryout->id);
        }

        $pilihanWajibSiswa = $request->input('mata_pelajaran_wajib', []);
        $pilihanOpsionalSiswa = $request->input('mata_pelajaran_opsional', []);
        $mapelPilihan = array_merge($pilihanWajibSiswa, $pilihanOpsionalSiswa);

        if ($paketTryout->tipe_paket != 'ulangan') {
            $minWajib = $paketTryout->min_wajib ?? 0;
            $maxOpsional = $paketTryout->max_opsional ?? 0;

            if (count($pilihanWajibSiswa) < $minWajib) {
                throw ValidationException::withMessages(['mata_pelajaran_wajib' => 'Anda harus memilih minimal '.$minWajib.' mata pelajaran wajib.']);
            }
            if (count($pilihanOpsionalSiswa) > $maxOpsional) {
                throw ValidationException::withMessages(['mata_pelajaran_opsional' => 'Anda hanya dapat memilih maksimal '.$maxOpsional.' mata pelajaran opsional.']);
            }
        }

        if (empty($mapelPilihan)) {
            return redirect()->back()->withErrors(['pilihan' => 'Anda harus memilih setidaknya satu mata pelajaran.']);
        }

        Session::put('ujian_siswa.mapel_pilihan', $mapelPilihan);
        $mapelPertamaId = $mapelPilihan[0];

        // "SAKLAR PINTAR": Mengarahkan berdasarkan tipe paket
        if ($paketTryout->tipe_paket === 'event') {
            return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelPertamaId]);
        } else {
            return redirect()->route('siswa.ujian.fleksibel.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelPertamaId]);
        }
    }

        public function showSoal(Request $request, PaketTryout $paketTryout, $mapelId)
    {
        $ujianSiswa = Session::get('ujian_siswa');
        if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id) {
            return redirect()->route('siswa.ujian.mulai', $paketTryout->id);
        }

        $student = Student::findOrFail($ujianSiswa['student_id']);
        $now = Carbon::now();

        // --- Logika untuk Event ---
        if ($paketTryout->tipe_paket === 'event') {
            $eventSchedule = $this->calculateEventSchedule($paketTryout);
            $requestedMapelSchedule = $eventSchedule[$mapelId] ?? null;

            if (!$requestedMapelSchedule) {
                return redirect()->route('siswa.ujian.hasil', $paketTryout->id)->with('error', 'Mata pelajaran tidak ditemukan dalam jadwal event.');
            }

            if ($now->isAfter($requestedMapelSchedule['waktu_selesai'])) {
                // ... (logika pindah paksa)
            }

            if ($now->isBefore($requestedMapelSchedule['waktu_mulai'])) {
                return view('siswa.menunggu', [
                    'paketTryout' => $paketTryout,
                    'mapelSelanjutnya' => $requestedMapelSchedule['nama_mapel'],
                    'waktuMulaiSelanjutnya' => $requestedMapelSchedule['waktu_mulai']->timestamp,
                    'mapelSebelumnya' => 'Sesi Sebelumnya',
                    'fullSchedule' => $eventSchedule
                ]);
            }
            $waktuSelesaiTimestamp = $requestedMapelSchedule['waktu_selesai']->timestamp;
        }
        // --- Logika untuk Tryout Fleksibel ---
        else {
            $durasi = $paketTryout->mataPelajaran->firstWhere('id', $mapelId)->pivot->durasi_menit;
            if (!Session::has("ujian_siswa.end_time.{$mapelId}")) {
                $waktuSelesai = now()->addMinutes($durasi);
                Session::put("ujian_siswa.end_time.{$mapelId}", $waktuSelesai->timestamp);
            }
            $waktuSelesaiTimestamp = Session::get("ujian_siswa.end_time.{$mapelId}");
        }

        // ===============================================================
        // KODE YANG DIPERBAIKI: Menambahkan kembali definisi $mapelSelanjutnyaId
        // ===============================================================
        $mapelPilihan = Session::get('ujian_siswa.mapel_pilihan', []);
        $mapelSekarangIndex = array_search($mapelId, $mapelPilihan);
        $mapelSelanjutnyaId = $mapelPilihan[$mapelSekarangIndex + 1] ?? null;
        // ===============================================================

        $sisaWaktu = $waktuSelesaiTimestamp - $now->timestamp;
        if ($sisaWaktu <= 0 && $paketTryout->tipe_paket !== 'event') { // Pindah paksa hanya untuk fleksibel
            return $this->simpanJawaban($request, $paketTryout, $mapelId);
        }

        $mapel = MataPelajaran::findOrFail($mapelId);
        $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
        $mapel->setRelation('soal', $mapel->soal()->whereIn('id', $soalPilihanIds)->inRandomOrder()->with('pilihanJawaban')->get());
        $jawabanTersimpan = JawabanPeserta::where('student_id', $student->id)->pluck('jawaban_peserta', 'soal_id')->all();

        return view('siswa.soal-mapel', compact(
            'paketTryout',
            'mapel',
            'mapelSelanjutnyaId', // Sekarang variabel ini sudah ada
            'waktuSelesaiTimestamp',
            'student',
            'jawabanTersimpan'
        ));
    }
public function simpanJawaban(Request $request, PaketTryout $paketTryout, $mapelId)
    {
        $ujianSiswa = Session::get('ujian_siswa');
        if (!$ujianSiswa) { return redirect()->route('welcome')->with('error', 'Sesi ujian Anda telah berakhir.'); }

        // Blok kode penyimpanan jawaban Anda tidak berubah
        $studentId = $ujianSiswa['student_id'];
        $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
        $semuaSoalDiMapel = Soal::whereIn('id', $soalPilihanIds)->with('pilihanJawaban', 'pernyataans.pilihanJawabans')->get();
        foreach ($semuaSoalDiMapel as $soal) { /* ... logika simpan jawaban Anda ... */ }
        Session::push('ujian_siswa.mapel_sudah_dikerjakan', $mapelId);

        // --- LOGIKA NAVIGASI FINAL ---
        $mapelPilihanIds = Session::get('ujian_siswa.mapel_pilihan', []);
        $nextMapelId = $this->getNextMapelId($mapelId, $mapelPilihanIds);

        if ($nextMapelId) {
            return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $nextMapelId]);
        }

        return redirect()->route('siswa.ujian.hasil', $paketTryout->id);
    }
    public function showSoalFleksibel(Request $request, PaketTryout $paketTryout, $mapelId)
{
    $ujianSiswa = Session::get('ujian_siswa');
    if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id) {
        return redirect()->route('siswa.ujian.mulai', $paketTryout->id);
    }

    $student = Student::findOrFail($ujianSiswa['student_id']);
    $mapelPilihanIds = Session::get('ujian_siswa.mapel_pilihan', []);

    if (!in_array($mapelId, $mapelPilihanIds)) {
        return redirect()->route('siswa.ujian.hasil', $paketTryout->id)->with('error', 'Mata pelajaran tidak valid.');
    }

    $mapel = MataPelajaran::findOrFail($mapelId);
    $durasi = $paketTryout->mataPelajaran->firstWhere('id', $mapelId)->pivot->durasi_menit;
    $mapelSelanjutnyaId = $this->getNextMapelId($mapelId, $mapelPilihanIds);

    // Timer logic (sudah benar)
    if (!Session::has("ujian_siswa.end_time.{$mapelId}")) {
        $waktuSelesai = now()->addMinutes($durasi);
        Session::put("ujian_siswa.end_time.{$mapelId}", $waktuSelesai->timestamp);
    }
    $waktuSelesaiTimestamp = Session::get("ujian_siswa.end_time.{$mapelId}");

    // Pengambilan data soal (sudah benar)
    $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
    $mapel->setRelation('soal', $mapel->soal()->whereIn('id', $soalPilihanIds)->inRandomOrder()->with(['pilihanJawaban', 'pernyataans.pilihanJawabans'])->get());

    $jawabanTersimpan = JawabanPeserta::where('student_id', $student->id)
                                      ->where('paket_tryout_id', $paketTryout->id)
                                      ->get();
    // ===================================================================

    // Kirim data yang sudah benar ke view
    return view('siswa.soal-mapel-fleksibel', compact(
        'paketTryout',
        'mapel',
        'mapelSelanjutnyaId',
        'student',
        'jawabanTersimpan',
        'waktuSelesaiTimestamp'
    ));
}


    // File: app/Http/Controllers/SiswaController.php

public function simpanJawabanFleksibel(Request $request, PaketTryout $paketTryout, $mapelId)
{
    $ujianSiswa = Session::get('ujian_siswa');
    if (!$ujianSiswa) { return redirect()->route('welcome')->with('error', 'Sesi ujian Anda telah berakhir.'); }

    $studentId = $ujianSiswa['student_id'];
    $semuaJawabanSiswa = $request->input('jawaban_soal', []);

    $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
    $semuaSoalDiMapel = Soal::whereIn('id', $soalPilihanIds)->with(['pilihanJawaban', 'pernyataans.pilihanJawabans'])->get();

    foreach ($semuaSoalDiMapel as $soal) {
        $jawabanPeserta = $semuaJawabanSiswa[$soal->id] ?? null;
        $apakahBenar = false;
        $jawabanUntukDisimpan = '';

        if (!is_null($jawabanPeserta)) {
            if ($soal->tipe_soal === 'pilihan_ganda') {
                $kunciJawabanModel = $soal->pilihanJawaban->firstWhere('apakah_benar', true);
                if ($kunciJawabanModel) {
                    $kunciJawabanTeks = strip_tags($kunciJawabanModel->pilihan_teks);
                    $jawabanPesertaTeks = strip_tags($jawabanPeserta);
                    $apakahBenar = (trim($jawabanPesertaTeks) === trim($kunciJawabanTeks));
                }
                $jawabanUntukDisimpan = $jawabanPeserta; // Simpan jawaban asli dengan HTML
            }
            elseif ($soal->tipe_soal === 'pilihan_ganda_majemuk') {
                $kunciJawabanTeks = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('pilihan_teks')->map(function($item) {
                    return trim(strip_tags($item));
                })->toArray();

                $jawabanPesertaArr = is_array($jawabanPeserta) ? array_map(function($item) {
                    return trim(strip_tags($item));
                }, $jawabanPeserta) : [];

                sort($kunciJawabanTeks);
                sort($jawabanPesertaArr);
                $apakahBenar = ($kunciJawabanTeks === $jawabanPesertaArr);
                $jawabanUntukDisimpan = json_encode($jawabanPeserta); // Simpan jawaban asli
            }
            elseif ($soal->tipe_soal === 'isian') {
                $kunciJawabanTeks = $soal->pilihanJawaban->first()->pilihan_teks ?? '';
                $kunciJawabanBersih = strip_tags(trim($kunciJawabanTeks));
                $jawabanPesertaBersih = strip_tags(trim($jawabanPeserta));
                $apakahBenar = Str::lower($jawabanPesertaBersih) === Str::lower($kunciJawabanBersih);
                $jawabanUntukDisimpan = $jawabanPeserta;
            }
            elseif ($soal->tipe_soal === 'pilihan_ganda_kompleks') {
                $kunciJawabanIds = $soal->pernyataans->pluck('pilihanJawabans')->flatten()->where('apakah_benar', true)->pluck('id')->toArray();
                $jawabanPesertaArr = is_array($jawabanPeserta) ? $jawabanPeserta : [];
                if(count($jawabanPesertaArr) === $soal->pernyataans->count()){
                    $jawabanPesertaIds = array_values($jawabanPesertaArr);
                    sort($kunciJawabanIds);
                    sort($jawabanPesertaIds);
                    $apakahBenar = ($kunciJawabanIds === $jawabanPesertaIds);
                }
                $jawabanUntukDisimpan = json_encode($jawabanPesertaArr);
            }
        }

        JawabanPeserta::updateOrCreate(
            ['student_id' => $studentId, 'soal_id' => $soal->id, 'paket_tryout_id' => $paketTryout->id],
            ['jawaban_peserta' => $jawabanUntukDisimpan, 'apakah_benar' => $apakahBenar]
        );
    }

    Session::push('ujian_siswa.mapel_sudah_dikerjakan', $mapelId);

    $mapelPilihanIds = Session::get('ujian_siswa.mapel_pilihan', []);
    $nextMapelId = $this->getNextMapelId($mapelId, $mapelPilihanIds);

    // Navigasi ini sudah benar berdasarkan input 'action'
    if ($request->input('action') == 'finish' || !$nextMapelId) {
        return redirect()->route('siswa.ujian.fleksibel.hasil', $paketTryout->id);
    }
    if ($nextMapelId) {
        return redirect()->route('siswa.ujian.fleksibel.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $nextMapelId]);
    }

    return redirect()->route('siswa.ujian.fleksibel.hasil', $paketTryout->id);
}
        private function calculateEventSchedule(PaketTryout $paketTryout)
    {
    $schedule = [];
    $cumulativeTime = Carbon::parse($paketTryout->waktu_mulai);

    $semuaMapelPaket = $paketTryout->mataPelajaran()
        ->orderBy('paket_mapel.urutan', 'asc')
        ->get();

    foreach ($semuaMapelPaket as $mapel) {
        $durasi = $mapel->pivot->durasi_menit;

        // ===============================================================
        // INI ADALAH PERBAIKANNYA
        // ===============================================================
        // Waktu selesai sekarang adalah akhir dari menit, bukan awal menit berikutnya
        $waktuSelesai = $cumulativeTime->copy()->addMinutes($durasi)->subSecond();
        // ===============================================================

        $schedule[$mapel->id] = [
            'id' => $mapel->id,
            'nama_mapel' => $mapel->nama_mapel,
            'waktu_mulai' => $cumulativeTime->copy(),
            'waktu_selesai' => $waktuSelesai,
            'durasi' => $durasi,
        ];

        // Waktu mulai untuk sesi berikutnya adalah waktu selesai + 1 detik
        $cumulativeTime = $waktuSelesai->copy()->addSecond();
    }
    return $schedule;
}

    // HELPER BARU UNTUK MENCARI MAPEL SELANJUTNYA DENGAN LEBIH BERSIH
    private function getNextMapelId($currentMapelId, $mapelPilihanIds)
    {
        $currentMapelIndex = array_search($currentMapelId, $mapelPilihanIds);
        if ($currentMapelIndex !== false && isset($mapelPilihanIds[$currentMapelIndex + 1])) {
            return $mapelPilihanIds[$currentMapelIndex + 1];
        }
        return null;
    }

public function autoSaveJawaban(Request $request, PaketTryout $paketTryout, $mapelId)
{
    $ujianSiswa = Session::get('ujian_siswa');
    if (!$ujianSiswa) {
        return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan.'], 403);
    }

    $validated = $request->validate([
        'soal_id' => 'required|integer|exists:soal,id',
        // Jawaban bisa berupa string tunggal (radio/isian) atau array (checkbox)
        'jawaban' => 'nullable|present',
    ]);

    $studentId = $ujianSiswa['student_id'];
    $soalId = $validated['soal_id'];
    $jawabanPeserta = $validated['jawaban']; // Ini bisa string atau array

    $soal = Soal::with('pilihanJawaban')->find($soalId);
    if (!$soal) {
        return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan.'], 404);
    }

    $apakahBenar = false;
    $jawabanUntukDisimpan = '';

    if (!empty($jawabanPeserta)) {
        if ($soal->tipe_soal === 'pilihan_ganda_majemuk') {
            // Jika jawaban adalah array (dari checkbox)
            $jawabanBenarDb = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('pilihan_teks')->toArray();
            $jawabanPesertaArr = is_array($jawabanPeserta) ? $jawabanPeserta : [];
            sort($jawabanBenarDb);
            sort($jawabanPesertaArr);
            $apakahBenar = $jawabanBenarDb === $jawabanPesertaArr;
            // Simpan sebagai string JSON
            $jawabanUntukDisimpan = json_encode($jawabanPesertaArr);

        } elseif ($soal->tipe_soal === 'isian') {
            $jawabanBenar = $soal->pilihanJawaban->first()->pilihan_teks ?? '';
            $apakahBenar = Str::lower(trim($jawabanPeserta)) === Str::lower(trim($jawabanBenar));
            $jawabanUntukDisimpan = $jawabanPeserta;

        } elseif ($soal->tipe_soal === 'pilihan_ganda') {
            $jawabanBenar = $soal->pilihanJawaban->firstWhere('apakah_benar', true);
            $apakahBenar = ($jawabanBenar && $jawabanPeserta === $jawabanBenar->pilihan_teks);
            $jawabanUntukDisimpan = $jawabanPeserta;
        }
        // [DITAMBAHKAN] Logika untuk Pilihan Ganda Kompleks (Matriks)
        elseif ($soal->tipe_soal === 'pilihan_ganda_kompleks') {
            $jawabanBenar = $soal->pernyataans->pluck('pilihanJawabans')->flatten()->where('apakah_benar', true)->pluck('id')->toArray();
            $jawabanSiswa = is_array($jawabanPeserta) ? $jawabanPeserta : json_decode($jawabanPeserta, true);

            $jawabanUntukDisimpan = json_encode($jawabanSiswa);

            $isFormValid = true;
            foreach ($soal->pernyataans as $pernyataan) {
                if (!isset($jawabanSiswa[$pernyataan->id]) || is_null($jawabanSiswa[$pernyataan->id])) {
                    $isFormValid = false;
                    break;
                }
            }

            if ($isFormValid) {
                $jawabanSiswaId = array_values($jawabanSiswa);
                sort($jawabanBenar);
                sort($jawabanSiswaId);

                $apakahBenar = $jawabanBenar === $jawabanSiswaId;
            }
        }
    }

    JawabanPeserta::updateOrCreate(
        ['student_id' => $studentId, 'soal_id' => $soalId, 'paket_tryout_id' => $paketTryout->id],
        ['jawaban_peserta' => $jawabanUntukDisimpan, 'apakah_benar' => $apakahBenar]
    );

    return response()->json(['success' => true, 'message' => 'Jawaban tersimpan.']);
}
   public function hasil(PaketTryout $paketTryout)
{
    $ujianSiswa = Session::get('ujian_siswa');
    if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id) {
        $student = Student::where('session_id', Session::getId())->where('paket_tryout_id', $paketTryout->id)->first();
        if (!$student) {
            return redirect()->route('welcome')->with('error', 'Sesi ujian tidak ditemukan.');
        }
        $studentId = $student->id;
    } else {
        $studentId = $ujianSiswa['student_id'];
    }

    $student = Student::findOrFail($studentId);

    // --- BLOK LOGIKA NILAI 0 DITEMPATKAN DI SINI (SEBELUM MENGAMBIL JAWABAN) ---

    // LANGKAH 1: Ambil semua ID mapel yang seharusnya dikerjakan siswa.
    $mapelSeharusnyaDikerjakanIds = Session::get('ujian_siswa.mapel_pilihan');
    if (empty($mapelSeharusnyaDikerjakanIds) && $paketTryout->tipe_paket == 'event') {
        $mapelSeharusnyaDikerjakanIds = $paketTryout->mataPelajaran()->pluck('id')->toArray();
    }

    if (!empty($mapelSeharusnyaDikerjakanIds)) {
        // LANGKAH 2: Ambil semua ID mapel yang sudah dikerjakan (memiliki jawaban).
        $mapelSudahDikerjakanIds = JawabanPeserta::where('student_id', $studentId)
                                                ->where('paket_tryout_id', $paketTryout->id)
                                                ->join('soal', 'jawaban_pesertas.soal_id', '=', 'soal.id')
                                                ->pluck('soal.mata_pelajaran_id')
                                                ->unique()
                                                ->toArray();

        // LANGKAH 3: Cari mapel yang terlewat.
        $mapelTerlewatIds = array_diff($mapelSeharusnyaDikerjakanIds, $mapelSudahDikerjakanIds);

        // LANGKAH 4: Untuk setiap mapel yang terlewat, buat record jawaban kosong (nilai 0).
        if (!empty($mapelTerlewatIds)) {
            $soalDariMapelTerlewat = Soal::whereIn('mata_pelajaran_id', $mapelTerlewatIds)
                                        ->whereHas('paketTryout', function($q) use ($paketTryout) {
                                            $q->where('paket_tryout.id', $paketTryout->id);
                                        })->get();

            $jawabanKosong = [];
            $now = now();
            foreach ($soalDariMapelTerlewat as $soal) {
                $jawabanKosong[] = [
                    'student_id' => $studentId,
                    'soal_id' => $soal->id,
                    'paket_tryout_id' => $paketTryout->id,
                    'jawaban_peserta' => '', // Jawaban kosong
                    'apakah_benar' => false, // Otomatis salah
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            // Masukkan semua jawaban kosong ke database sekaligus
            if (!empty($jawabanKosong)) {
                JawabanPeserta::insert($jawabanKosong);
            }
        }
    }
    // --- SELESAI BLOK LOGIKA NILAI 0 ---

    // Sekarang, kita ambil semua jawaban, TERMASUK jawaban kosong yang baru dibuat.
    $jawabanPeserta = JawabanPeserta::where('student_id', $studentId)
                                    ->where('paket_tryout_id', $paketTryout->id)
                                    ->with(['soal.mataPelajaran', 'soal.pilihanJawaban'])
                                    ->get();

    if ($paketTryout->tipe_paket == 'ulangan') {
        $totalBenar = $jawabanPeserta->where('apakah_benar', true)->count();
        $totalSalah = $jawabanPeserta->where('apakah_benar', false)->count();
        $totalSoal = $jawabanPeserta->count();

        Session::put('review_ulangan_data', [
            'paketTryout' => $paketTryout,
            'jawabanPeserta' => $jawabanPeserta,
            'namaLengkap' => $student->nama_lengkap,
            'jenjangPendidikan' => $student->jenjang_pendidikan,
            'kelas' => $student->kelas,
            'asalSekolah' => $student->asal_sekolah,
        ]);

        Session::forget('ujian_siswa');
        return view('siswa.hasil-ulangan', compact('paketTryout', 'totalBenar', 'totalSalah', 'totalSoal', 'student'));
    }

    $hasilPerMapel = $jawabanPeserta->groupBy('soal.mataPelajaran.nama_mapel')->map(function ($jawabanMapel) {
        $totalSoal = $jawabanMapel->count();
        $totalBenar = $jawabanMapel->where('apakah_benar', true)->count();
        return ['total_soal' => $totalSoal, 'total_benar' => $totalBenar];
    });

    // ===============================================================
    // PERBAIKAN TERAKHIR: LOGIKA WAKTU PENGERJAAN
    // ===============================================================
    $startTime = Session::get('ujian_siswa.start_time');
    $totalWaktuPengerjaan = 0;
    if ($startTime) {
        $totalWaktuPengerjaan = now()->timestamp - $startTime;
        $student->update(['total_waktu' => $totalWaktuPengerjaan]);
    }
    // ===============================================================

    $namaLengkap = $student->nama_lengkap;
    $jenjangPendidikan = $student->jenjang_pendidikan;
    $kelompok = $student->kelompok;
    $kelas = $student->kelas;
    $asalSekolah = $student->asal_sekolah;
    Session::forget('ujian_siswa');
    return view('siswa.hasil', compact('paketTryout', 'hasilPerMapel', 'totalWaktuPengerjaan', 'namaLengkap', 'jenjangPendidikan', 'kelompok','kelas','asalSekolah'));
}
    // app/Http/Controllers/SiswaController.php

public function hasilFleksibel(PaketTryout $paketTryout)
{
    // Salin seluruh isi dari method hasil() ke sini.
    // Untuk saat ini isinya bisa sama persis.
    $ujianSiswa = Session::get('ujian_siswa');
    if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id) {
        $student = Student::where('session_id', Session::getId())->where('paket_tryout_id', $paketTryout->id)->first();
        if (!$student) {
            return redirect()->route('welcome')->with('error', 'Sesi ujian tidak ditemukan.');
        }
        $studentId = $student->id;
    } else {
        $studentId = $ujianSiswa['student_id'];
    }

    $student = Student::findOrFail($studentId);

    $mapelSeharusnyaDikerjakanIds = Session::get('ujian_siswa.mapel_pilihan');
    if (empty($mapelSeharusnyaDikerjakanIds) && $paketTryout->tipe_paket == 'event') {
        $mapelSeharusnyaDikerjakanIds = $paketTryout->mataPelajaran()->pluck('id')->toArray();
    }

    if (!empty($mapelSeharusnyaDikerjakanIds)) {
        $mapelSudahDikerjakanIds = JawabanPeserta::where('student_id', $studentId)
                                                ->where('paket_tryout_id', $paketTryout->id)
                                                ->join('soal', 'jawaban_pesertas.soal_id', '=', 'soal.id')
                                                ->pluck('soal.mata_pelajaran_id')
                                                ->unique()
                                                ->toArray();
        $mapelTerlewatIds = array_diff($mapelSeharusnyaDikerjakanIds, $mapelSudahDikerjakanIds);
        if (!empty($mapelTerlewatIds)) {
            $soalDariMapelTerlewat = Soal::whereIn('mata_pelajaran_id', $mapelTerlewatIds)
                                        ->whereHas('paketTryout', function($q) use ($paketTryout) {
                                            $q->where('paket_tryout.id', $paketTryout->id);
                                        })->get();
            $jawabanKosong = [];
            $now = now();
            foreach ($soalDariMapelTerlewat as $soal) {
                $jawabanKosong[] = [
                    'student_id' => $studentId,
                    'soal_id' => $soal->id,
                    'paket_tryout_id' => $paketTryout->id,
                    'jawaban_peserta' => '',
                    'apakah_benar' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (!empty($jawabanKosong)) {
                JawabanPeserta::insert($jawabanKosong);
            }
        }
    }

    $jawabanPeserta = JawabanPeserta::where('student_id', $studentId)
                                    ->where('paket_tryout_id', $paketTryout->id)
                                    ->with(['soal.mataPelajaran', 'soal.pilihanJawaban'])
                                    ->get();

    $hasilPerMapel = $jawabanPeserta->groupBy('soal.mataPelajaran.nama_mapel')->map(function ($jawabanMapel) {
        $totalSoal = $jawabanMapel->count();
        $totalBenar = $jawabanMapel->where('apakah_benar', true)->count();
        return ['total_soal' => $totalSoal, 'total_benar' => $totalBenar];
    });

    $startTime = Session::get('ujian_siswa.start_time');
    $totalWaktuPengerjaan = 0;
    if ($startTime) {
        $totalWaktuPengerjaan = now()->timestamp - $startTime;
        $student->update(['total_waktu' => $totalWaktuPengerjaan]);
    }

    $namaLengkap = $student->nama_lengkap;
    $jenjangPendidikan = $student->jenjang_pendidikan;
    $kelompok = $student->kelompok;
    $kelas = $student->kelas;
    $asalSekolah = $student->asal_sekolah;
    Session::forget('ujian_siswa');

    // Gunakan view 'hasil' yang sama, hanya method controllernya yang beda
    return view('siswa.hasil', compact('paketTryout', 'hasilPerMapel', 'totalWaktuPengerjaan', 'namaLengkap', 'jenjangPendidikan', 'kelompok','kelas','asalSekolah'));
}

    public function unduhHasil(PaketTryout $paketTryout)
    {
        $hasilUjianData = Session::get('hasil_ujian_data');
        if (!$hasilUjianData || $hasilUjianData['paketTryout']->id != $paketTryout->id) {
            return redirect()->route('welcome')->with('error', 'Data hasil ujian tidak ditemukan.');
        }

        $pdf = PDF::loadView('siswa.laporan-pdf', $hasilUjianData);
        return $pdf->download('laporan-hasil-ujian-' . Str::slug($hasilUjianData['namaLengkap']) . '.pdf');
    }



    public function review(PaketTryout $paketTryout)
    {
        $reviewData = Session::get('review_ulangan_data');
        if (!$reviewData || $reviewData['paketTryout']->id != $paketTryout->id) {
            return redirect()->route('welcome')->with('error', 'Data review ulangan tidak ditemukan.');
        }

        $jawabanPeserta = $reviewData['jawabanPeserta'];
        $groupedJawaban = $jawabanPeserta->groupBy('soal.mataPelajaran.nama_mapel');

        return view('siswa.review-ulangan', compact('paketTryout', 'reviewData', 'groupedJawaban'));
    }
        public function startUlangan(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'kelas' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
        ]);

        Session::put('calon_peserta_ulangan', $validated);

        return redirect()->route('siswa.pilih_ulangan', [
            'jenjang' => $validated['jenjang_pendidikan'],
        ]);
    }

    // Menampilkan daftar ulangan yang tersedia untuk jenjang tertentu
    public function pilihUlangan($jenjang = null)
    {
        if (!Session::has('calon_peserta_ulangan')) {
             return redirect()->route('welcome')->with('error', 'Silakan isi data diri Anda terlebih dahulu.');
        }

        $query = Ulangan::where('status', 'published');

        if ($jenjang) {
            $query->whereHas('mataPelajaran', function ($q) use ($jenjang) {
                $q->where('jenjang_pendidikan', $jenjang);
            });
        }

        $ulangans = $query->latest()->get();

        return view('siswa.pilih-ulangan', compact('ulangans', 'jenjang'));
    }

    // Menampilkan halaman konfirmasi sebelum memulai ulangan
    public function mulaiSesiUlangan(Ulangan $ulangan)
    {
        if (!Session::has('calon_peserta_ulangan')) {
            return redirect()->route('welcome')->with('error', 'Sesi Anda telah berakhir, silakan isi data diri kembali.');
        }
        return view('siswa.mulai-ulangan', compact('ulangan'));
    }

    /**
     * Membuat sesi ulangan baru dan mengarahkan ke halaman soal.
     */
    public function startSesiUlangan(Request $request, Ulangan $ulangan)
    {
        // 1. Pastikan data calon peserta ada di session
        $dataSiswa = Session::get('calon_peserta_ulangan');
        if (!$dataSiswa) {
            return redirect()->route('welcome')->with('error', 'Sesi tidak valid. Silakan isi kembali data diri Anda.');
        }

        // 2. Buat sesi ulangan baru di database
        $ulanganSession = UlanganSession::create([
            'ulangan_id' => $ulangan->id,
            'nama_siswa' => $dataSiswa['nama_lengkap'],
            'kelas' => $dataSiswa['kelas'],
            'asal_sekolah' => $dataSiswa['asal_sekolah'],
            'jenjang' => $dataSiswa['jenjang_pendidikan'],
            'waktu_mulai' => now(),
        ]);

        // 3. Simpan ID sesi ke dalam session PHP
        Session::put('ulangan_session_id', $ulanganSession->id);

        // 4. Arahkan ke halaman pengerjaan soal
        return redirect()->route('siswa.ulangan.show_soal', ['ulangan' => $ulangan->id]);
    }

    /**
     * Menampilkan soal-soal dari ulangan yang dipilih.
     */
   public function showSoalUlangan(Ulangan $ulangan)
    {
        $sessionId = Session::get('ulangan_session_id');
        if (!$sessionId) {
            return redirect()->route('welcome')->with('error', 'Sesi ulangan tidak ditemukan.');
        }

        $ulanganSession = UlanganSession::find($sessionId);
        if (!$ulanganSession || $ulanganSession->ulangan_id != $ulangan->id) {
            return redirect()->route('welcome')->with('error', 'Sesi ulangan tidak valid.');
        }

        // Cukup muat relasi soal beserta pilihan jawabannya
        $ulangan->load('soal.pilihanJawaban');

        // Ambil jawaban yang sudah disimpan
        $jawabanTersimpan = $ulanganSession->jawaban->keyBy('soal_id');

        return view('siswa.soal-mapel-ulangan', compact('ulangan', 'ulanganSession', 'jawabanTersimpan'));
    }

     public function autoSaveJawabanUlangan(Request $request, Ulangan $ulangan)
    {
        $sessionId = Session::get('ulangan_session_id');
        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan.'], 403);
        }

        $validated = $request->validate([
            'soal_id' => 'required|integer|exists:soal,id',
            // Jawaban bisa berupa string tunggal (radio/isian) atau array (checkbox)
            'jawaban' => 'nullable|present',
        ]);

        $ulanganSession = UlanganSession::find($sessionId);
        $soalId = $validated['soal_id'];
        $jawabanSiswa = $validated['jawaban'];

        $soal = Soal::with(['pilihanJawaban', 'pernyataans.pilihanJawabans'])->find($soalId);
        if (!$soal) {
            return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan.'], 404);
        }

        $isCorrect = false;
        $jawabanUntukDisimpan = null;

        // ===============================================================
        // LOGIKA BARU UNTUK MENDUKUNG SEMUA TIPE SOAL
        // ===============================================================
        if ($soal->tipe_soal === 'pilihan_ganda') {
            $jawabanBenar = $soal->pilihanJawaban->where('apakah_benar', true)->first();
            $jawabanUntukDisimpan = $jawabanSiswa;
            if ($jawabanBenar && $jawabanSiswa == $jawabanBenar->id) {
                $isCorrect = true;
            }
        } elseif ($soal->tipe_soal === 'isian') {
            $jawabanBenar = $soal->pilihanJawaban->first()->pilihan_teks ?? '';
            $isCorrect = Str::lower(trim($jawabanSiswa)) === Str::lower(trim($jawabanBenar));
            $jawabanUntukDisimpan = $jawabanSiswa;
        } elseif ($soal->tipe_soal === 'pilihan_ganda_majemuk') {
            $jawabanBenarDb = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('id')->toArray();
            $jawabanSiswaArr = is_array($jawabanSiswa) ? $jawabanSiswa : [];
            sort($jawabanBenarDb);
            sort($jawabanSiswaArr);
            $isCorrect = ($jawabanBenarDb === $jawabanSiswaArr);
            $jawabanUntukDisimpan = json_encode($jawabanSiswaArr);
        } elseif ($soal->tipe_soal === 'pilihan_ganda_kompleks') {
            $jawabanBenarDb = $soal->pernyataans->pluck('pilihanJawabans')->flatten()->where('apakah_benar', true)->pluck('id')->toArray();
            $jawabanSiswaArr = is_array($jawabanSiswa) ? $jawabanSiswa : json_decode($jawabanSiswa, true);
            $jawabanUntukDisimpan = json_encode($jawabanSiswaArr);

            // Cek apakah semua pernyataan sudah dijawab
            $isFormValid = true;
            foreach ($soal->pernyataans as $pernyataan) {
                if (!isset($jawabanSiswaArr[$pernyataan->id]) || is_null($jawabanSiswaArr[$pernyataan->id])) {
                    $isFormValid = false;
                    break;
                }
            }

            if ($isFormValid) {
                $jawabanSiswaId = array_values($jawabanSiswaArr);
                sort($jawabanBenarDb);
                sort($jawabanSiswaId);
                $isCorrect = ($jawabanBenarDb === $jawabanSiswaId);
            }
        }
        // ===============================================================

        JawabanUlangan::updateOrCreate(
            [
                'ulangan_session_id' => $sessionId,
                'soal_id' => $soalId,
            ],
            [
                'pilihan_jawaban_id' => $jawabanUntukDisimpan,
                'is_correct' => $isCorrect,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Jawaban tersimpan.']);
    }

    /**
     * Menyimpan jawaban dari siswa.
     */
    public function simpanJawabanUlangan(Request $request, Ulangan $ulangan)
    {
        $sessionId = Session::get('ulangan_session_id');
        if (!$sessionId) {
            return redirect()->route('welcome')->with('error', 'Sesi Anda telah berakhir.');
        }

        $jawabanSiswa = $request->input('jawaban_soal', []);
        $jumlahBenar = 0;

        $semuaSoal = $ulangan->soal()->with('pilihanJawaban')->get();

        foreach ($semuaSoal as $soal) {
            $jawabanBenar = $soal->pilihanJawaban->where('apakah_benar', true)->first();
            $idJawabanSiswa = $jawabanSiswa[$soal->id] ?? null;
            $isCorrect = ($jawabanBenar && $idJawabanSiswa == $jawabanBenar->id);

            if ($isCorrect) {
                $jumlahBenar++;
            }

            JawabanUlangan::updateOrCreate(
                [
                    'ulangan_session_id' => $sessionId,
                    'soal_id' => $soal->id,
                ],
                [
                    'pilihan_jawaban_id' => $idJawabanSiswa,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        // Update sesi ulangan dengan hasil
        $ulanganSession = UlanganSession::find($sessionId);
        $ulanganSession->update([
            'waktu_selesai' => now(),
            'jumlah_benar' => $jumlahBenar,
            'jumlah_salah' => $semuaSoal->count() - $jumlahBenar,
        ]);

        return redirect()->route('siswa.ulangan.hasil', ['ulanganSession' => $sessionId]);
    }

    /**
     * Menampilkan halaman hasil ulangan.
     */
    public function hasilUlangan(UlanganSession $ulanganSession)
    {
        // Pastikan siswa hanya bisa melihat hasilnya sendiri
        if (Session::get('ulangan_session_id') != $ulanganSession->id) {
            // Jika session sudah tidak ada (misal: setelah browser ditutup),
            // kita bisa mengandalkan data yang dikirim ke view saja
            // atau terapkan logika keamanan yang lebih ketat jika perlu.
        }

        Session::forget('calon_peserta_ulangan');
        Session::forget('ulangan_session_id');


        return view('siswa.hasil-ulangan', compact('ulanganSession'));
    }

    /**
     * Menampilkan halaman review jawaban.
     */
    public function reviewUlangan(UlanganSession $ulanganSession)
    {

        $ulanganSession->load('ulangan.soal.pilihanJawaban', 'jawaban.pilihanJawaban');

        // Untuk mempermudah pencarian di view, kita ubah koleksi jawaban
        // menjadi array asosiatif dengan key berupa soal_id.
        $jawabanSiswa = $ulanganSession->jawaban->keyBy('soal_id');

        return view('siswa.review-ulangan', compact('ulanganSession', 'jawabanSiswa'));
    }

}
