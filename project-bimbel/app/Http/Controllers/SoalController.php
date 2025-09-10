<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SoalController extends Controller
{
    // Menampilkan halaman untuk memilih mata pelajaran
    public function pilihMataPelajaran(Request $request, $jenjang)
    {
        $mataPelajaran = MataPelajaran::where('jenjang_pendidikan', $jenjang)->orderBy('nama_mapel', 'asc')->get();
        return view('soal.pilih-mata-pelajaran', compact('mataPelajaran', 'jenjang'));
    }

    public function index(MataPelajaran $mataPelajaran)
    {
        $soalItems = $mataPelajaran->soal()
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('soal.index', compact('mataPelajaran', 'soalItems'));
    }

    public function create(MataPelajaran $mataPelajaran)
    {
        return view('soal.create', compact('mataPelajaran'));
    }

    public function store(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,isian,pilihan_ganda_majemuk',
            'status' => 'required|in:aktif,nonaktif',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'pilihan' => ['required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk', 'array', 'min:2'],
            'pilihan.*' => ['required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk', 'nullable', 'string'],
            'jawaban_benar' => ['required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk', 'nullable'],

            'jawaban_isian' => ['required_if:tipe_soal,isian', 'nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($request, $mataPelajaran) {
                $gambarSoalPath = null;
                if ($request->hasFile('gambar_soal')) {
                    $gambarSoalPath = $request->file('gambar_soal')->store('soal-images', 'public');
                }

                $soal = $mataPelajaran->soal()->create([
                    'user_id' => auth()->id(),
                    'pertanyaan' => clean($request->pertanyaan),
                    'gambar_path' => $gambarSoalPath,
                    'tipe_soal' => $request->tipe_soal,
                    'status' => $request->status,
                ]);

                if ($request->tipe_soal == 'pilihan_ganda' || $request->tipe_soal == 'pilihan_ganda_majemuk') {
                    $jawabanBenar = is_array($request->jawaban_benar) ? $request->jawaban_benar : [$request->jawaban_benar];

                    foreach ($request->pilihan as $index => $pilihanTeks) {
                        $gambarPilihanPath = null;
                        if ($request->hasFile('gambar_pilihan.' . $index)) {
                            $gambarPilihanPath = $request->file('gambar_pilihan.' . $index)->store('pilihan-images', 'public');
                        }

                        $soal->pilihanJawaban()->create([
                            'pilihan_teks' => clean($pilihanTeks),
                            'gambar_path' => $gambarPilihanPath,
                            'apakah_benar' => in_array(strval($index), $jawabanBenar, true),
                        ]);
                    }
                } elseif ($request->tipe_soal == 'isian') {
                    $soal->pilihanJawaban()->create([
                        'pilihan_teks' => clean($request->jawaban_isian),
                        'apakah_benar' => true,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan soal. Pastikan semua input terisi dengan benar. Pesan Error: '.$e->getMessage())->withInput();
        }

        return redirect()->route('mata-pelajaran.soal.index', $mataPelajaran->id)
                         ->with('success', 'Soal baru berhasil ditambahkan.');
    }
    public function show(Soal $soal)
    {
        $soal->load('pilihanJawaban');
        return view('soal.show', compact('soal'));
    }

    public function edit(Soal $soal)
    {
        $soal->load('pilihanJawaban');
        return view('soal.edit', compact('soal'));
    }

    public function update(Request $request, Soal $soal)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'tipe_soal' => 'required|in:pilihan_ganda,isian,pilihan_ganda_majemuk',
            'status' => 'required|in:aktif,nonaktif',
            'gambar_soal' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'pilihan' => ['required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk', 'array', 'min:2'],
            'pilihan.*' => ['required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk', 'nullable', 'string'],
            'jawaban_benar' => ['required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk', 'nullable'],

            'jawaban_isian' => ['required_if:tipe_soal,isian', 'nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($request, $soal) {
                $gambarSoalPath = $soal->gambar_path;
                if ($request->hasFile('gambar_soal')) {
                    if ($gambarSoalPath) {
                        Storage::disk('public')->delete($gambarSoalPath);
                    }
                    $gambarSoalPath = $request->file('gambar_soal')->store('soal-images', 'public');
                }

                $soal->update([
                    'pertanyaan' => clean($request->pertanyaan),
                    'gambar_path' => $gambarSoalPath,
                    'tipe_soal' => $request->tipe_soal,
                    'status' => $request->status,
                ]);

                $soal->pilihanJawaban()->delete();

                if ($request->tipe_soal == 'pilihan_ganda' || $request->tipe_soal == 'pilihan_ganda_majemuk') {
                    $jawabanBenar = is_array($request->jawaban_benar) ? $request->jawaban_benar : [$request->jawaban_benar];

                    foreach ($request->pilihan as $index => $pilihanTeks) {
                        $gambarPilihanPath = null;
                        if ($request->hasFile('gambar_pilihan.' . $index)) {
                            // Anda mungkin perlu menambahkan logika untuk menghapus gambar lama jika ada
                            $gambarPilihanPath = $request->file('gambar_pilihan.' . $index)->store('pilihan-images', 'public');
                        }

                        $soal->pilihanJawaban()->create([
                            'pilihan_teks' => clean($pilihanTeks),
                            'gambar_path' => $gambarPilihanPath,
                            'apakah_benar' => in_array(strval($index), $jawabanBenar, true),
                        ]);
                    }
                } elseif ($request->tipe_soal == 'isian') {
                    $soal->pilihanJawaban()->create([
                        'pilihan_teks' => clean($request->jawaban_isian),
                        'apakah_benar' => true,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui soal. Pastikan semua input terisi dengan benar. Pesan Error: '.$e->getMessage())->withInput();
        }

        return redirect()->route('soal.show', $soal->id)
                         ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $mataPelajaranId = $soal->mata_pelajaran_id;
        $soal->delete();
        return redirect()->route('mata-pelajaran.soal.index', $mataPelajaranId)
                         ->with('success', 'Soal berhasil dihapus.');
    }
}
