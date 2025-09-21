<?php
// app/Http/Controllers/UlanganController.php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\Ulangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UlanganController extends Controller
{
    // ... (fungsi pilihJenjang, index, create, store tetap sama)
    public function pilihJenjang()
    {
        return view('ulangan.pilih-jenjang');
    }

    public function index(Request $request)
    {
        $jenjang = $request->query('jenjang');
        $query = Ulangan::query()->with('mataPelajaran', 'soal');
        if ($jenjang) {
            $query->whereHas('mataPelajaran', function ($q) use ($jenjang) {
                $q->where('jenjang_pendidikan', $jenjang);
            });
        }
        $ulangans = $query->latest()->paginate(10);
        return view('ulangan.index', compact('ulangans', 'jenjang'));
    }

    public function create(Request $request)
    {
        $jenjang = $request->query('jenjang');
        if (!$jenjang) {
            return redirect()->route('ulangan.pilihJenjang')->with('error', 'Silakan pilih jenjang pendidikan terlebih dahulu.');
        }
        $mataPelajarans = MataPelajaran::where('jenjang_pendidikan', $jenjang)->orderBy('nama_mapel')->get();
        return view('ulangan.create', compact('mataPelajarans', 'jenjang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ulangan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'jenjang' => 'required|in:SD,SMP,SMA',
        ]);
        $ulangan = Ulangan::create($request->all());
        return redirect()->route('ulangan.show', $ulangan)->with('success', 'Ulangan berhasil dibuat. Silakan tambahkan soal.');
    }


    /**
     * Menampilkan halaman detail untuk mengelola soal pada ulangan.
     */
    public function show(Ulangan $ulangan)
    {
        // 1. Ambil soal yang sudah terpilih untuk ulangan ini
        $soalTerpilihIds = $ulangan->soal()->pluck('soal.id')->toArray();

        // 2. Ambil soal yang tersedia dari bank soal
        //    - Sesuai mata pelajaran ulangan
        //    - Yang belum ada di daftar soal terpilih
        $soalTersedia = Soal::where('mata_pelajaran_id', $ulangan->mata_pelajaran_id)
                            ->whereNotIn('id', $soalTerpilihIds)
                            ->get();

        // 3. Kirim data ke view
        return view('ulangan.show', [
            'ulangan' => $ulangan,
            'soalTersedia' => $soalTersedia
        ]);
    }

    /**
     * Mengubah status ulangan (draft/published).
     */
    public function toggleStatus(Ulangan $ulangan)
    {
        $ulangan->status = ($ulangan->status == 'draft') ? 'published' : 'draft';
        $ulangan->save();

        return back()->with('success', 'Status ulangan berhasil diperbarui.');
    }

    /**
     * Menambahkan soal ke dalam ulangan.
     */
        public function addSoal(Request $request, Ulangan $ulangan)
    {
        try {
            // Validasi input
            $request->validate(['soal_id' => 'required|exists:soal,id']);

            $soalId = $request->input('soal_id');
            $ulangan->soal()->attach($soalId);

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil ditambahkan ke ulangan.'
            ], 200);

        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut di server
            Log::error('Gagal menambahkan soal: ' . $e->getMessage(), ['ulangan_id' => $ulangan->id, 'soal_id' => $soalId ?? 'N/A']);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan soal. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Menghapus soal dari ulangan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ulangan  $ulangan
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeSoal(Request $request, Ulangan $ulangan)
    {
        try {
            // Validasi input
            $request->validate(['soal_id' => 'required|exists:soal,id']);

            $soalId = $request->input('soal_id');
            $ulangan->soal()->detach($soalId);

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil dihapus dari ulangan.'
            ], 200);

        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut di server
            Log::error('Gagal menghapus soal: ' . $e->getMessage(), ['ulangan_id' => $ulangan->id, 'soal_id' => $soalId ?? 'N/A']);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus soal. Silakan coba lagi.'
            ], 500);
        }
    }

    // ... (fungsi edit, update, destroy tetap sama)
    public function edit(Ulangan $ulangan)
    {
        $jenjang = $ulangan->mataPelajaran->jenjang_pendidikan;
        $mataPelajarans = MataPelajaran::where('jenjang_pendidikan', $jenjang)->orderBy('nama_mapel')->get();
        return view('ulangan.edit', compact('ulangan', 'mataPelajarans', 'jenjang'));
    }

    public function update(Request $request, Ulangan $ulangan)
    {
        $request->validate([
            'nama_ulangan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        ]);
        $ulangan->update($request->only(['nama_ulangan', 'deskripsi', 'mata_pelajaran_id']));
        return redirect()->route('ulangan.index', ['jenjang' => $ulangan->mataPelajaran->jenjang_pendidikan])->with('success', 'Detail ulangan berhasil diperbarui.');
    }

    public function destroy(Ulangan $ulangan)
    {
        $jenjang = $ulangan->mataPelajaran->jenjang_pendidikan;
        $ulangan->delete();
        return redirect()->route('ulangan.index', ['jenjang' => $jenjang])->with('success', 'Ulangan berhasil dihapus.');
    }
    public function updateKesulitan(Ulangan $ulangan)
    {
        // 1. Logika perhitungan sama persis dengan method analysis
        $soalIds = $ulangan->soal()->pluck('soal.id');
        $analisisSoal = Soal::whereIn('id', $soalIds)
            ->withCount([
                'jawabanUlangan as jumlah_benar' => function ($query) use ($ulangan) {
                    $query->where('is_correct', true)
                          ->whereIn('ulangan_session_id', $ulangan->sessions()->pluck('id'));
                },
                'jawabanUlangan as jumlah_menjawab' => function ($query) use ($ulangan) {
                    $query->whereIn('ulangan_session_id', $ulangan->sessions()->pluck('id'));
                }
            ])
            ->get();

        // 2. Loop melalui setiap soal yang sudah dianalisis
        foreach ($analisisSoal as $soal) {
            if ($soal->jumlah_menjawab > 0) {
                $persentaseBenar = ($soal->jumlah_benar / $soal->jumlah_menjawab) * 100;
            } else {
                $persentaseBenar = 0;
            }

            $tingkatKesulitanOtomatis = 'Sedang'; // Default
            if ($persentaseBenar > 75) {
                $tingkatKesulitanOtomatis = 'Mudah';
            } elseif ($persentaseBenar <= 35) {
                $tingkatKesulitanOtomatis = 'Sulit';
            }

            // 3. Update kolom 'tingkat_kesulitan' di tabel 'soal'
            $soalToUpdate = Soal::find($soal->id);
            if ($soalToUpdate) {
                $soalToUpdate->tingkat_kesulitan = $tingkatKesulitanOtomatis;
                $soalToUpdate->save();
            }
        }

        // 4. Kembali ke halaman analisis dengan pesan sukses
        return redirect()->route('ulangan.laporan.analysis', $ulangan)
                         ->with('success', 'Rating kesulitan soal berhasil diperbarui di Bank Soal!');
    }
}
