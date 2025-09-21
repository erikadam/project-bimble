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
        // 1. Ambil semua soal yang ada di ulangan ini.
        $soalIds = $ulangan->soal()->pluck('soal.id');

        // 2. Hitung statistik untuk setiap soal.
        $analisisSoal = Soal::whereIn('id', $soalIds)
            ->withCount([
                // Hitung total jawaban benar untuk soal ini di semua sesi ulangan terkait
                'jawabanUlangan as jumlah_benar' => function ($query) use ($ulangan) {
                    $query->where('is_correct', true)
                          ->whereIn('ulangan_session_id', $ulangan->sessions()->pluck('id'));
                },
                // Hitung total jawaban (benar + salah)
                'jawabanUlangan as jumlah_menjawab' => function ($query) use ($ulangan) {
                    $query->whereIn('ulangan_session_id', $ulangan->sessions()->pluck('id'));
                }
            ])
            ->get();

        // 3. Hitung persentase dan tentukan tingkat kesulitan
        $analisisSoal->each(function ($soal) {
            if ($soal->jumlah_menjawab > 0) {
                $soal->persentase_benar = ($soal->jumlah_benar / $soal->jumlah_menjawab) * 100;
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
