<?php

namespace App\Http\Controllers;

use App\Models\Ulangan;
use App\Models\Soal;
use App\Models\JawabanUlangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanUlanganController extends Controller
{
    /**
     * Menampilkan daftar sesi pengerjaan (responses) dari para siswa untuk sebuah ulangan.
     */
    public function responses(Ulangan $ulangan)
    {
        // Memuat semua sesi yang terkait dengan ulangan ini, diurutkan dari yang terbaru.
        $sessions = $ulangan->sessions()->latest()->paginate(20);

        return view('ulangan.laporan.responses', compact('ulangan', 'sessions'));
    }

    /**
     * Menampilkan analisis butir soal berdasarkan jawaban semua peserta.
     */
    public function analysis(Ulangan $ulangan)
    {
        // 1. Ambil semua soal yang ada di ulangan ini, dengan eager loading untuk soal matriks
        $soalIds = $ulangan->soal()->pluck('soal.id');
        $analisisSoal = Soal::whereIn('id', $soalIds)
            ->with(['pilihanJawaban', 'pernyataans.pilihanJawabans'])
            ->get();

        // 2. Ambil semua jawaban untuk ulangan ini
        $allJawaban = JawabanUlangan::whereIn('ulangan_session_id', $ulangan->sessions()->pluck('id'))
                                     ->with('soal.pilihanJawaban')
                                     ->get();

        // Hitung total peserta yang menjawab
        $totalSessions = $ulangan->sessions()->count();


        $analisisSoal->each(function ($soal) use ($allJawaban, $totalSessions) {
            $jawabanPerSoal = $allJawaban->where('soal_id', $soal->id);
            $jumlahBenar = $jawabanPerSoal->where('is_correct', true)->count();
            $jumlahMenjawab = $jawabanPerSoal->count();

            // [DITAMBAHKAN] Logika analisis untuk soal matriks
            if ($soal->tipe_soal === 'pilihan_ganda_kompleks') {
                $soal->pernyataans->each(function ($pernyataan) use ($jawabanPerSoal, $totalSessions) {
                    $pernyataan->pilihanJawabans->each(function ($pilihan) use ($pernyataan, $jawabanPerSoal, $totalSessions) {
                        $count = $jawabanPerSoal->where('pilihan_jawaban_id', $pilihan->id)->count();
                        $pilihan->jumlah_pilih = $count;
                        $pilihan->persentase_pilih = $totalSessions > 0 ? ($count / $totalSessions) * 100 : 0;
                    });
                });
            }

            if ($jumlahMenjawab > 0) {
                $soal->persentase_benar = ($jumlahBenar / $jumlahMenjawab) * 100;
            } else {
                $soal->persentase_benar = 0;
            }

            // Logika penentuan Tingkat Kesulitan (bisa disesuaikan)
            if ($soal->persentase_benar > 75) {
                $soal->tingkat_kesulitan_otomatis = 'Mudah';
            } elseif ($soal->persentase_benar > 35) {
                $soal->tingkat_kesulitan_otomatis = 'Sedang';
            } else {
                $soal->tingkat_kesulitan_otomatis = 'Sulit';
            }
        });

        return view('ulangan.laporan.analysis', compact('ulangan', 'analisisSoal'));
    }
}
