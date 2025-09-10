<?php

namespace App\Http\Controllers;

use App\Models\PaketTryout;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\JawabanPeserta;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SiswaController extends Controller
{
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
        $jenjang = $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? null;
        return view('siswa.mulai', compact('paketTryout', 'jenjang'));
    }

    public function start(Request $request, PaketTryout $paketTryout)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
            'kelompok' => 'required|string',
        ]);
        $sessionId = Str::uuid()->toString();
        $student = Student::create(['paket_tryout_id' => $paketTryout->id, 'nama_lengkap' => $validated['nama_lengkap'], 'jenjang_pendidikan' => $validated['jenjang_pendidikan'], 'kelompok' => $validated['kelompok'], 'session_id' => $sessionId]);
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
        $mataPelajaran = $paketTryout->mataPelajaran()->orderBy('is_wajib', 'desc')->get();
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

        $minWajib = $paketTryout->min_wajib ?? 0;
        $maxOpsional = $paketTryout->max_opsional ?? 0;

        if (count($pilihanWajibSiswa) < $minWajib) {
            throw ValidationException::withMessages(['mata_pelajaran_wajib' => 'Anda harus memilih minimal '.$minWajib.' mata pelajaran wajib.']);
        }

        if (count($pilihanOpsionalSiswa) > $maxOpsional) {
            throw ValidationException::withMessages(['mata_pelajaran_opsional' => 'Anda hanya dapat memilih maksimal '.$maxOpsional.' mata pelajaran opsional.']);
        }

        $mapelPilihan = array_merge($pilihanWajibSiswa, $pilihanOpsionalSiswa);

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
        } else {
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
        // Jika sesi tidak ada sama sekali, kembalikan ke halaman utama
        return redirect()->route('welcome')->with('error', 'Sesi ujian Anda tidak ditemukan atau telah berakhir.');
    }

    $studentId = $ujianSiswa['student_id'];

    // Ambil SEMUA soal yang seharusnya dikerjakan siswa di mapel ini
    $soalPilihanIds = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapelId)->pluck('soal.id');
    $semuaSoalDiMapel = Soal::whereIn('id', $soalPilihanIds)->with('pilihanJawaban')->get();

    foreach ($semuaSoalDiMapel as $soal) {
        $jawabanPeserta = $request->input('jawaban_soal.' . $soal->id);
        $apakahBenar = false;
        $jawabanUntukDisimpan = '';

        // Hanya proses jika siswa memberikan jawaban
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

        // Gunakan updateOrCreate untuk menyimpan jawaban atau membuat record baru jika tidak dijawab
        JawabanPeserta::updateOrCreate(
            ['student_id' => $studentId, 'soal_id' => $soal->id, 'paket_tryout_id' => $paketTryout->id],
            ['jawaban_peserta' => $jawabanUntukDisimpan, 'apakah_benar' => $apakahBenar]
        );
    }

    Session::push('ujian_siswa.mapel_sudah_dikerjakan', $mapelId);
    Session::forget("ujian_siswa.end_time.{$mapelId}");

    // Logika untuk lanjut ke mapel berikutnya atau halaman tunggu...
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
        if ($mapelSelanjutnyaId) {
            return redirect()->route('siswa.ujian.show_soal', ['paketTryout' => $paketTryout->id, 'mapelId' => $mapelSelanjutnyaId]);
        }
    }

    $student = Student::findOrFail($studentId);
    if (!isset($student->total_waktu)) {
        $totalWaktuPengerjaan = now()->timestamp - Carbon::parse($student->created_at)->timestamp;
        $student->update(['total_waktu' => $totalWaktuPengerjaan]);
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
        $jawabanPeserta = JawabanPeserta::where('student_id', $studentId)->where('paket_tryout_id', $paketTryout->id)->with(['soal.mataPelajaran'])->get();
        $hasilPerMapel = $jawabanPeserta->groupBy('soal.mataPelajaran.nama_mapel')->map(function ($jawabanMapel) {
            $totalSoal = $jawabanMapel->count();
            $totalBenar = $jawabanMapel->where('apakah_benar', true)->count();
            return ['total_soal' => $totalSoal, 'total_benar' => $totalBenar];
        });
        $totalWaktuPengerjaan = $student->total_waktu ?? 0;
        $namaLengkap = $student->nama_lengkap;
        $jenjangPendidikan = $student->jenjang_pendidikan;
        $kelompok = $student->kelompok;
        Session::forget('ujian_siswa');
        return view('siswa.hasil', compact('paketTryout', 'hasilPerMapel', 'totalWaktuPengerjaan', 'namaLengkap', 'jenjangPendidikan', 'kelompok'));
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
}
