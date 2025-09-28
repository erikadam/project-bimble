<?php

namespace App\Http\Controllers;

use App\Models\PaketTryout;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\JawabanPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class UjianController extends Controller
{
    // Menampilkan halaman form data diri untuk memulai demo
    public function mulai(PaketTryout $paketTryout)
    {
        $jenjang = $paketTryout->mataPelajaran->first()->jenjang_pendidikan ?? 'N/A';
        return view('ujian.mulai', compact('paketTryout', 'jenjang'));
    }

    // Mengelola data diri dan mengarahkan ke mata pelajaran pertama
    public function start(Request $request, PaketTryout $paketTryout)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:255',
        ]);

        $sessionId = Str::uuid()->toString();

        Session::put('ujian_demo', [
            'session_id' => $sessionId,
            'nama_siswa' => $validated['nama_siswa'],
            'paket_id' => $paketTryout->id,
            'mapel_sekarang' => null,
            'mapel_sudah_dikerjakan' => [],
        ]);

        $mapelList = $paketTryout->mataPelajaran()
                                ->orderBy('is_wajib', 'desc')
                                ->pluck('mata_pelajaran.id')
                                ->toArray();

        if (empty($mapelList)) {
            return redirect()->route('demo.ujian.hasil', $paketTryout->id);
        }

        return redirect()->route('demo.ujian.show_mapel', [
            'paketTryout' => $paketTryout->id,
            'mapelId' => $mapelList[0],
        ]);
    }

    // Menampilkan halaman soal untuk satu mata pelajaran
    public function showMapel(PaketTryout $paketTryout, $mapelId)
    {
        $ujianSession = Session::get('ujian_demo');

        if (!$ujianSession || $ujianSession['paket_id'] != $paketTryout->id) {
            return redirect()->route('demo.ujian.mulai', $paketTryout->id);
        }

        $mapel = MataPelajaran::with(['soal' => function ($query) {
            $query->where('status', 'aktif')->with('pilihanJawaban');
        }])->findOrFail($mapelId);

        $durasi = $paketTryout->mataPelajaran->firstWhere('id', $mapelId)->pivot->durasi_menit;

        $waktuSelesai = Session::get("ujian_demo.end_time.{$mapelId}");

        if (!$waktuSelesai) {
            $waktuSelesai = now()->addMinutes($durasi)->timestamp;
            Session::put("ujian_demo.end_time.{$mapelId}", $waktuSelesai);
        }

        $sisaWaktu = $waktuSelesai - now()->timestamp;

        if ($sisaWaktu <= 0) {
            return Redirect::route('demo.ujian.simpan_jawaban', [
                'paketTryout' => $paketTryout->id,
                'mapelId' => $mapelId,
            ]);
        }

        $mapelUrutan = $paketTryout->mataPelajaran()->orderBy('is_wajib', 'desc')->pluck('mata_pelajaran.id')->toArray();
        $mapelSekarangIndex = array_search($mapelId, $mapelUrutan);
        $mapelSelanjutnyaId = $mapelUrutan[$mapelSekarangIndex + 1] ?? null;

        Session::put('ujian_demo.mapel_sekarang', $mapelId);

        return view('ujian.soal-mapel', compact('paketTryout', 'mapel', 'durasi', 'mapelSelanjutnyaId', 'sisaWaktu'));
    }

    // Menyimpan jawaban dan mengarahkan ke mata pelajaran selanjutnya
public function simpanJawabanMapel(Request $request, PaketTryout $paketTryout, $mapelId)
{
    $ujianSession = Session::get('ujian_demo');

    if (!$ujianSession || $ujianSession['paket_id'] != $paketTryout->id) {
        return redirect()->route('demo.ujian.mulai', $paketTryout->id)->with('error', 'Sesi ujian tidak valid.');
    }

    $sessionId = $ujianSession['session_id'];

    // Hapus jawaban lama HANYA untuk mata pelajaran yang sedang dikerjakan.
    JawabanPeserta::where('session_id', $sessionId)
        ->where('paket_tryout_id', $paketTryout->id)
        ->whereIn('soal_id', function($query) use ($mapelId) {
            $query->select('id')->from('soal')->where('mata_pelajaran_id', $mapelId);
        })->delete();

    $soalList = Soal::where('mata_pelajaran_id', $mapelId)
                    ->where('status', 'aktif')
                    ->with('pilihanJawaban')
                    ->get();

    foreach ($soalList as $soal) {
        $jawabanPeserta = $request->input('jawaban_soal.' . $soal->id);
        $apakahBenar = false;
        $jawabanUntukDisimpan = ''; // Variabel baru untuk menyimpan jawaban

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
                // Logika untuk jawaban majemuk
                $jawabanBenarDb = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('pilihan_teks')->toArray();
                $jawabanPesertaArr = is_array($jawabanPeserta) ? $jawabanPeserta : [];

                sort($jawabanBenarDb);
                sort($jawabanPesertaArr);

                $apakahBenar = $jawabanBenarDb === $jawabanPesertaArr;
                $jawabanUntukDisimpan = json_encode($jawabanPesertaArr); // Simpan sebagai JSON
            }
        }

        JawabanPeserta::create([
            'paket_tryout_id' => $paketTryout->id,
            'soal_id' => $soal->id,
            'jawaban_peserta' => $jawabanUntukDisimpan,
            'apakah_benar' => $apakahBenar,
            'session_id' => $sessionId,
            'waktu_pengerjaan' => 0,
        ]);
    }

    Session::push('ujian_demo.mapel_sudah_dikerjakan', $mapelId);
    Session::forget("ujian_demo.end_time.{$mapelId}");

    $mapelUrutan = $paketTryout->mataPelajaran()->orderBy('is_wajib', 'desc')->pluck('mata_pelajaran.id')->toArray();
    $mapelSekarangIndex = array_search($mapelId, $mapelUrutan);
    $mapelSelanjutnyaId = $mapelUrutan[$mapelSekarangIndex + 1] ?? null;

    if ($mapelSelanjutnyaId) {
        return redirect()->route('demo.ujian.show_mapel', [
            'paketTryout' => $paketTryout->id,
            'mapelId' => $mapelSelanjutnyaId,
        ]);
    }

    return redirect()->route('demo.ujian.hasil', $paketTryout->id);
}

    // Menampilkan halaman hasil ujian
    public function hasil(PaketTryout $paketTryout)
    {
        $ujianSession = Session::get('ujian_demo');

        if (!$ujianSession || $ujianSession['paket_id'] != $paketTryout->id) {
            return redirect()->route('paket-tryout.demo_index')->with('error', 'Sesi ujian tidak ditemukan.');
        }

        $sessionId = $ujianSession['session_id'];

        $jawabanPeserta = JawabanPeserta::where('session_id', $sessionId)
            ->where('paket_tryout_id', $paketTryout->id)
            ->get();

        $totalSoal = $jawabanPeserta->count();
        $totalBenar = $jawabanPeserta->where('apakah_benar', true)->count();
        $skor = $totalSoal > 0 ? ($totalBenar / $totalSoal) * 100 : 0;

        Session::forget('ujian_demo');

        return view('ujian.hasil', compact('paketTryout', 'jawabanPeserta', 'totalSoal', 'totalBenar', 'skor'));
    }
}
