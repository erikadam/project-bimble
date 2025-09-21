<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use App\Models\SoalPernyataan;
use App\Models\PernyataanPilihanJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    public function pilihMataPelajaran(Request $request, $jenjang)
    {
        $mataPelajaran = MataPelajaran::where('jenjang_pendidikan', $jenjang)->orderBy('nama_mapel', 'asc')->get();
        return view('soal.pilih-mata-pelajaran', compact('mataPelajaran', 'jenjang'));
    }

    public function index(MataPelajaran $mataPelajaran)
    {
        // PERBAIKAN: Hanya eager-load relasi yang dibutuhkan untuk halaman index
        $soalItems = $mataPelajaran->soal()->with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('soal.index', compact('mataPelajaran', 'soalItems'));
    }

    public function create(MataPelajaran $mataPelajaran)
    {
        return view('soal.create', compact('mataPelajaran'));
    }

    public function show(Soal $soal)
    {
        // Tetap eager-load semua relasi di sini karena halaman show membutuhkannya
        $soal->load(['pilihanJawaban', 'pernyataans.pilihanJawabans']);
        return view('soal.show', compact('soal'));
    }

        public function edit(Soal $soal)
    {
        // PERBAIKAN: Eager load semua relasi yang mungkin dibutuhkan oleh halaman edit
        $soal->load(['pilihanJawaban', 'pernyataans.pilihanJawabans']);
        return view('soal.edit', compact('soal'));
    }

    public function destroy(Soal $soal)
{
    $mataPelajaran = $soal->mataPelajaran;
    if ($soal->gambar_path) {
        Storage::disk('public')->delete($soal->gambar_path);
    }
    $soal->delete();
    return redirect()->route('mata-pelajaran.soal.index', $mataPelajaran->id)->with('success', 'Soal berhasil dihapus.');
}

    public function store(Request $request, MataPelajaran $mataPelajaran)
    {
        $validatedData = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,pilihan_ganda_majemuk,isian,pilihan_ganda_kompleks',
            'gambar_soal' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $soal = new Soal([
                'mata_pelajaran_id' => $mataPelajaran->id,
                'user_id' => Auth::id(),
                'pertanyaan' => $request->pertanyaan,
                'tipe_soal' => $request->tipe_soal,
                'status' => 'aktif',
            ]);

            if ($request->hasFile('gambar_soal')) {
                $soal->gambar_path = $request->file('gambar_soal')->store('soal-images', 'public');
            }
            $soal->save();

            if ($request->tipe_soal == 'pilihan_ganda_kompleks') {
                $this->savePilihanGandaKompleks($soal, $request);
            } else {
                $this->savePilihanGandaStandard($soal, $request);
            }

            DB::commit();
            return redirect()->route('mata-pelajaran.soal.index', $mataPelajaran->id)->with('success', 'Soal berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyimpan soal: " . $e->getMessage() . " di baris " . $e->getLine());
            return redirect()->back()->with('error', 'Gagal menyimpan soal. Detail: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Soal $soal)
    {
        $validatedData = $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,pilihan_ganda_majemuk,isian,pilihan_ganda_kompleks',
            'gambar_soal' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $soal->pertanyaan = $request->pertanyaan;
            $soal->tipe_soal = $request->tipe_soal;

            if ($request->hasFile('gambar_soal')) {
                if ($soal->gambar_path) Storage::disk('public')->delete($soal->gambar_path);
                $soal->gambar_path = $request->file('gambar_soal')->store('soal-images', 'public');
            }
            $soal->save();

            if ($request->tipe_soal == 'pilihan_ganda_kompleks') {
                $soal->pilihanJawaban()->delete();
                $this->savePilihanGandaKompleks($soal, $request);
            } else {
                $soal->pernyataans()->delete();
                $this->savePilihanGandaStandard($soal, $request);
            }

            DB::commit();
            return redirect()->route('mata-pelajaran.soal.index', $soal->mataPelajaran)->with('success', 'Soal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update soal: " . $e->getMessage() . " di baris " . $e->getLine());
            return redirect()->back()->with('error', 'Gagal memperbarui soal. Detail: ' . $e->getMessage())->withInput();
        }
    }

    private function savePilihanGandaStandard(Soal $soal, Request $request)
    {
        $pilihanIdsToKeep = [];
        if (!$request->has('pilihan')) {
             $soal->pilihanJawaban()->delete();
             return;
        };

        foreach ($request->pilihan as $key => $pilihanData) {
            if (empty($pilihanData['teks']) && !$request->hasFile("pilihan.{$key}.gambar") && empty($pilihanData['id'])) continue;

            $isBenar = false;
            if ($request->tipe_soal === 'pilihan_ganda') {
                $isBenar = ($request->jawaban_benar_radio == $key);
            } else {
                $isBenar = isset($pilihanData['benar']) && $pilihanData['benar'] == '1';
            }

            $pilihanModel = $soal->pilihanJawaban()->find($pilihanData['id'] ?? 0);
            $gambarPath = $pilihanModel->gambar_path ?? null;

            if ($request->hasFile("pilihan.{$key}.gambar")) {
                if ($gambarPath) Storage::disk('public')->delete($gambarPath);
                $gambarPath = $request->file("pilihan.{$key}.gambar")->store('pilihan-images', 'public');
            }

            $pilihan = $soal->pilihanJawaban()->updateOrCreate(
                ['id' => $pilihanData['id'] ?? null],
                [
                    'pilihan_teks' => $pilihanData['teks'] ?? '',
                    'apakah_benar' => $isBenar,
                    'gambar_path' => $gambarPath
                ]
            );
            $pilihanIdsToKeep[] = $pilihan->id;
        }
        $soal->pilihanJawaban()->whereNotIn('id', $pilihanIdsToKeep)->delete();
    }

    private function savePilihanGandaKompleks(Soal $soal, Request $request)
    {
        $pernyataanIdsToKeep = [];
        if (!$request->has('pernyataans') || !$request->has('kolom')) {
            $soal->pernyataans()->delete();
            return;
        };

        $soal->pilihan_kompleks = $request->kolom;
        $soal->save();

        foreach ($request->pernyataans as $pKey => $pernyataanData) {
            if (empty($pernyataanData['teks'])) continue;

            $pernyataan = $soal->pernyataans()->updateOrCreate(
                ['id' => $pernyataanData['id'] ?? null],
                ['pernyataan_teks' => $pernyataanData['teks']]
            );
            $pernyataanIdsToKeep[] = $pernyataan->id;

            $jawabanBenarIndex = $pernyataanData['jawaban_benar_radio'] ?? -1;

            $pernyataan->pilihanJawabans()->delete();
            foreach($request->kolom as $kKey => $kolomTeks) {
                 $pernyataan->pilihanJawabans()->create([
                    'pilihan_teks' => $kolomTeks,
                    'apakah_benar' => ($jawabanBenarIndex == $kKey)
                ]);
            }
        }
        $soal->pernyataans()->whereNotIn('id', $pernyataanIdsToKeep)->delete();
    }
}
