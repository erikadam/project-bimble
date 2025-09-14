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
                            ->with('guru');

        if ($jenjang) {
            $query->whereHas('mataPelajaran', function ($q) use ($jenjang) {
                $q->where('jenjang_pendidikan', $jenjang);
            });
        }
        $paketTryouts = $query->orderBy('waktu_mulai', 'asc')->get();

        $paketTryouts->each(function ($paket) {
            if ($paket->waktu_mulai) {
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
        return view('siswa.pilih-event', compact('paketTryouts', 'jenjang'));
    }

    public function mulai(PaketTryout $paketTryout)
    {
        // PERBAIKAN: Pengecekan session yang benar.
        if ($paketTryout->tipe_paket == 'ulangan') {
            // Jika ini adalah ulangan, pastikan data calon peserta ada di session.
            if (!Session::has('calon_peserta_ulangan')) {
                return redirect()->route('welcome')->with('error', 'Sesi Anda telah berakhir, silakan isi data diri kembali.');
            }
            // Jika session ada, langsung tampilkan halaman mulai ulangan.
            return view('siswa.mulai-ulangan', compact('paketTryout'));
        }

        // Logika untuk tipe paket selain ulangan (tryout, event) tetap sama.
        $jenjang = $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? null;
        return view('siswa.mulai', compact('paketTryout', 'jenjang'));
    }

    public function start(Request $request, PaketTryout $paketTryout)
    {
        // Validasi form berbeda untuk 'ulangan'
        if ($paketTryout->tipe_paket == 'ulangan') {
             $validated = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'kelas' => 'required|string|max:255',
                'asal_sekolah' => 'required|string|max:255',
                'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
            ]);
            $kelompok = $validated['kelas']; // Menggunakan 'kelas' sebagai 'kelompok' untuk konsistensi di database
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
        // Memasukkan 'kelas' dan 'asal_sekolah' ke model Student
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
        if ($paketTryout->tipe_paket == 'event') {
             $mapelPertama = $paketTryout->mataPelajaran()->orderBy('urutan', 'asc')->first();
             if ($mapelPertama) {
                return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelPertama->id]);
             }
             return redirect()->route('siswa.ujian.hasil', $paketTryout->id);
        }
        return redirect()->route('siswa.ujian.pilih_mapel', $paketTryout->id);
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

        $pilihanWajibSiswa = $request->input('mata_pelajaran_wajib') ?? [];
        $pilihanOpsionalSiswa = $request->input('mata_pelajaran_opsional') ?? [];
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
        return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelPilihan[0]]);
    }

    public function showSoal(Request $request, PaketTryout $paketTryout, $mapelId)
    {
        $ujianSiswa = Session::get('ujian_siswa');
        if (!$ujianSiswa || $ujianSiswa['paket_id'] != $paketTryout->id) {
            return redirect()->route('siswa.ujian.mulai', $paketTryout->id);
        }
        $student = Student::findOrFail($ujianSiswa['student_id']);
        $now = Carbon::now();
        $sisaWaktu = null; // Default value

        if ($paketTryout->tipe_paket == 'ulangan') {
            $mapelPilihan = $ujianSiswa['mapel_pilihan'];
            if (!in_array($mapelId, $mapelPilihan)) {
                return redirect()->route('siswa.ujian.pilih_mapel', $paketTryout->id);
            }
            $mapel = MataPelajaran::findOrFail($mapelId);
            $mapelSekarangIndex = array_search($mapelId, $mapelPilihan);
            $mapelSelanjutnyaId = $mapelPilihan[$mapelSekarangIndex + 1] ?? null;
            $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
            $mapel->setRelation('soal', $mapel->soal()->whereIn('id', $soalPilihanIds)->with('pilihanJawaban')->get());
            return view('siswa.soal-mapel-ulangan', compact('paketTryout', 'mapel', 'mapelSelanjutnyaId', 'sisaWaktu', 'student'));
        }

        // Logika untuk event dan tryout (kode lama)
        if ($paketTryout->tipe_paket == 'event') {
            $waktuMulaiEvent = Carbon::parse($paketTryout->waktu_mulai);
            $waktuSelesaiEvent = $waktuMulaiEvent->copy()->addMinutes($paketTryout->durasi_menit);
            if ($now->isBefore($waktuMulaiEvent)) {
                return redirect()->route('siswa.pilih_event', ['jenjang' => $student->jenjang_pendidikan])->with('error', 'Ujian "' . $paketTryout->nama_paket . '" belum dimulai.');
            }
            if ($now->isAfter($waktuSelesaiEvent)) {
                return redirect()->route('siswa.ujian.hasil', $paketTryout->id)->with('error', 'Waktu ujian telah berakhir.');
            }
            $mapelUrutan = $paketTryout->mataPelajaran()->orderBy('urutan', 'asc')->get();
            $waktuBerjalan = 0;
            $mapelAktif = null;
            $waktuSelesaiMapelAktif = null;
            $mapelSelanjutnyaId = null;
            foreach ($mapelUrutan as $index => $mapel) {
                $waktuMulaiMapel = $waktuMulaiEvent->copy()->addMinutes($waktuBerjalan);
                $waktuSelesaiMapel = $waktuMulaiMapel->copy()->addMinutes($mapel->pivot->durasi_menit);
                if ($now->between($waktuMulaiMapel, $waktuSelesaiMapel)) {
                    $mapelAktif = $mapel;
                    $waktuSelesaiMapelAktif = $waktuSelesaiMapel;
                    $mapelSelanjutnyaId = $mapelUrutan[$index + 1]->id ?? null;
                    break;
                }
                $waktuBerjalan += $mapel->pivot->durasi_menit;
            }
            if (!$mapelAktif) {
                return redirect()->route('siswa.ujian.hasil', $paketTryout->id);
            }
            if ($mapelAktif->id != $mapelId) {
                return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelAktif->id]);
            }
            $sisaWaktu = $now->diffInSeconds($waktuSelesaiMapelAktif, false);
            if ($sisaWaktu <= 0) {
                return $this->simpanJawaban($request, $paketTryout, $mapelId);
            }
            $mapel = $mapelAktif;
        } else { // Tipe paket tryout
            $mapelPilihan = $ujianSiswa['mapel_pilihan'];
            if (!in_array($mapelId, $mapelPilihan)) {
                return redirect()->route('siswa.ujian.pilih_mapel', $paketTryout->id);
            }
            $mapel = MataPelajaran::findOrFail($mapelId);
            $durasi = $paketTryout->mataPelajaran->firstWhere('id', $mapelId)->pivot->durasi_menit;
            if (!Session::has("ujian_siswa.end_time.{$mapelId}")) {
                Session::put("ujian_siswa.end_time.{$mapelId}", now()->addMinutes($durasi)->timestamp);
            }
            $waktuSelesai = Session::get("ujian_siswa.end_time.{$mapelId}");
            $sisaWaktu = $waktuSelesai - $now->timestamp;
            if ($sisaWaktu <= 0) {
                 return $this->simpanJawaban($request, $paketTryout, $mapelId);
            }
            $mapelSekarangIndex = array_search($mapelId, $mapelPilihan);
            $mapelSelanjutnyaId = $mapelPilihan[$mapelSekarangIndex + 1] ?? null;
        }
        $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
        $mapel->setRelation('soal', $mapel->soal()->whereIn('id', $soalPilihanIds)->inRandomOrder()->with('pilihanJawaban')->get());
        return view('siswa.soal-mapel', compact('paketTryout', 'mapel', 'mapelSelanjutnyaId', 'sisaWaktu', 'student'));
    }

    public function simpanJawaban(Request $request, PaketTryout $paketTryout, $mapelId)
    {
        $ujianSiswa = Session::get('ujian_siswa');
        if (!$ujianSiswa) {
            return redirect()->route('welcome')->with('error', 'Sesi ujian Anda tidak ditemukan atau telah berakhir.');
        }

        $studentId = $ujianSiswa['student_id'];
        $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
        $semuaSoalDiMapel = Soal::whereIn('id', $soalPilihanIds)->with('pilihanJawaban')->get();

        foreach ($semuaSoalDiMapel as $soal) {
            $jawabanPeserta = $request->input('jawaban_soal.' . $soal->id);
            $apakahBenar = false;
            $jawabanUntukDisimpan = '';

            if (!empty($jawabanPeserta)) {
                if ($soal->tipe_soal === 'isian') {
                    $jawabanBenar = $soal->pilihanJawaban->first()->pilihan_teks ?? '';
                    $apakahBenar = Str::lower($jawabanPeserta) === Str::lower($jawabanBenar);
                    $jawabanUntukDisimpan = $jawabanPeserta;
                } elseif ($soal->tipe_soal === 'pilihan_ganda') {
                    $jawabanBenar = $soal->pilihanJawaban->firstWhere('apakah_benar', true)->pilihan_teks ?? '';
                    $apakahBenar = $jawabanPeserta === $jawabanBenar;
                    $jawabanUntukDisimpan = $jawabanPeserta;
                } elseif ($soal->tipe_soal === 'pilihan_ganda_majemuk') {
                    $jawabanBenarDb = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('pilihan_teks')->toArray();
                    $jawabanPesertaArr = is_array($jawabanPeserta) ? $jawabanPeserta : [];
                    sort($jawabanBenarDb);
                    sort($jawabanPesertaArr);
                    $apakahBenar = $jawabanBenarDb === $jawabanPesertaArr;
                    $jawabanUntukDisimpan = json_encode($jawabanPesertaArr);
                }
            }

            JawabanPeserta::updateOrCreate(
                ['student_id' => $studentId, 'soal_id' => $soal->id, 'paket_tryout_id' => $paketTryout->id],
                ['jawaban_peserta' => $jawabanUntukDisimpan, 'apakah_benar' => $apakahBenar]
            );
        }

        Session::push('ujian_siswa.mapel_sudah_dikerjakan', $mapelId);
        Session::forget("ujian_siswa.end_time.{$mapelId}");

        if ($paketTryout->tipe_paket == 'event') {
            $mapelUrutan = $paketTryout->mataPelajaran()->orderBy('urutan', 'asc')->get();
            $mapelSekarangIndex = $mapelUrutan->search(fn($item) => $item->id == $mapelId);
            $mapelSelanjutnya = $mapelUrutan[$mapelSekarangIndex + 1] ?? null;

            if ($mapelSelanjutnya) {
                $waktuMulaiEvent = Carbon::parse($paketTryout->waktu_mulai);
                $waktuBerjalan = 0;
                for ($i = 0; $i <= $mapelSekarangIndex; $i++) {
                    $waktuBerjalan += $mapelUrutan[$i]->pivot->durasi_menit;
                }
                $waktuMulaiSelanjutnya = $waktuMulaiEvent->copy()->addMinutes($waktuBerjalan);
                if (Carbon::now()->isBefore($waktuMulaiSelanjutnya)) {
                    return view('siswa.menunggu', [
                        'mapelSebelumnya' => $mapelUrutan[$mapelSekarangIndex]->nama_mapel,
                        'mapelSelanjutnya' => $mapelSelanjutnya->nama_mapel,
                        'waktuMulaiSelanjutnya' => $waktuMulaiSelanjutnya->timestamp,
                    ]);
                }
                return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelSelanjutnya->id]);
            }
        } else {
            $mapelPilihan = Session::get('ujian_siswa.mapel_pilihan');
            $mapelSekarangIndex = array_search($mapelId, $mapelPilihan);
            $mapelSelanjutnyaId = $mapelPilihan[$mapelSekarangIndex + 1] ?? null;

            if ($paketTryout->tipe_paket == 'ulangan') {
                return redirect()->route('siswa.ujian.pilih_mapel', $paketTryout->id);
            }

            if ($mapelSelanjutnyaId) {
                return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelSelanjutnyaId]);
            }
        }

        $student = Student::findOrFail($studentId);
        if (!isset($student->total_waktu)) {
            $totalWaktuPengerjaan = now()->timestamp - Carbon::parse($student->created_at)->timestamp;
            $student->update(['total_waktu' => $totalWaktuPengerjaan]);
        }

        if ($paketTryout->tipe_paket == 'ulangan') {
             return redirect()->route('siswa.ujian.hasil', $paketTryout->id);
        }

        return redirect()->route('siswa.ujian.hasil', $paketTryout->id);
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
        $totalWaktuPengerjaan = $student->total_waktu ?? 0;
        $namaLengkap = $student->nama_lengkap;
        $jenjangPendidikan = $student->jenjang_pendidikan;
        $kelompok = $student->kelompok;
        $kelas = $student->kelas;
        $asalSekolah = $student->asal_sekolah;
        Session::forget('ujian_siswa');
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

        return view('siswa.soal-mapel-ulangan', compact('ulangan', 'ulanganSession'));
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
        // Memuat relasi yang dibutuhkan:
        // ulangan -> soal -> semua pilihan jawaban
        // jawaban (milik sesi ini) -> pilihan jawaban yang dipilih siswa
        $ulanganSession->load('ulangan.soal.pilihanJawaban', 'jawaban.pilihanJawaban');

        // Untuk mempermudah pencarian di view, kita ubah koleksi jawaban
        // menjadi array asosiatif dengan key berupa soal_id.
        $jawabanSiswa = $ulanganSession->jawaban->keyBy('soal_id');

        return view('siswa.review-ulangan', compact('ulanganSession', 'jawabanSiswa'));
    }
}
