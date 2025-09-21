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
use App\Models\SoalPernyataan;
use Illuminate\Support\Facades\Auth;
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
        // Mengubah dari get() menjadi paginate()
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

public function store(Request $request)
{
    // Validasi yang lebih cerdas dan lengkap
    $request->validate([
        'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        'pertanyaan' => 'required|string',
        'tipe_soal' => 'required|in:pilihan_ganda,isian,pilihan_ganda_majemuk,benar_salah_tabel',
        'tingkat_kesulitan' => 'required|in:Mudah,Sedang,Sulit',
        'gambar_soal' => 'nullable|image|max:2048',

        // Validasi ini hanya berjalan jika tipe soalnya sesuai
        'pilihan' => 'required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk,isian|array',
        'pilihan.*.teks' => 'required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk,isian|nullable|string',
        'pilihan.*.gambar' => 'nullable|image|max:2048',

        // Validasi ini hanya berjalan jika tipe soalnya tabel
        'pernyataan' => 'required_if:tipe_soal,benar_salah_tabel|array',
        'pernyataan.*.teks' => 'required_if:tipe_soal,benar_salah_tabel|nullable|string',
        'pernyataan.*.jawaban' => 'required_if:tipe_soal,benar_salah_tabel|in:benar,salah',
    ]);

    $gambarSoalPath = $request->hasFile('gambar_soal') ? $request->file('gambar_soal')->store('public/soal-images') : null;

    $soal = Soal::create([
        'mata_pelajaran_id' => $request->mata_pelajaran_id,
        'pertanyaan' => $request->pertanyaan,
        'tipe_soal' => $request->tipe_soal,
        'tingkat_kesulitan' => $request->tingkat_kesulitan,
        'gambar_path' => $gambarSoalPath,
        'user_id' => Auth::id(), // <-- PERBAIKAN: Menambahkan ID pengguna yang login
    ]);

    if ($request->tipe_soal == 'benar_salah_tabel') {
        if ($request->has('pernyataan')) {
            foreach ($request->pernyataan as $index => $item) {
                if (!empty($item['teks'])) {
                    $soal->pernyataan()->create([
                        'pernyataan' => $item['teks'],
                        'jawaban_benar' => $item['jawaban'] == 'benar',
                        'urutan' => $index + 1,
                    ]);
                }
            }
        }
    } else {
        if ($request->has('pilihan')) {
            foreach ($request->pilihan as $index => $pilihanData) {
                if (!empty($pilihanData['teks'])) {
                    $gambarPilihanPath = $request->hasFile("pilihan.{$index}.gambar") ? $request->file("pilihan.{$index}.gambar")->store('public/pilihan-images') : null;

                    $soal->pilihanJawaban()->create([
                        'pilihan_teks' => $pilihanData['teks'],
                        'apakah_benar' => isset($pilihanData['benar']),
                        'gambar_path' => $gambarPilihanPath,
                    ]);
                }
            }
        }
    }

    return redirect()->route('mata-pelajaran.soal.index', ['mata_pelajaran' => $soal->mata_pelajaran_id])
                     ->with('success', 'Soal berhasil ditambahkan.');
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
        'tingkat_kesulitan' => 'required|in:Mudah,Sedang,Sulit',
        // Validasi untuk pilihan ganda & isian
        'pilihan.*.teks' => 'required_if:tipe_soal,pilihan_ganda,pilihan_ganda_majemuk,isian',
        'pilihan.*.benar' => 'nullable',
        // Validasi untuk soal tabel
        'pernyataan.*.teks' => 'required_if:tipe_soal,benar_salah_tabel|string',
        'pernyataan.*.jawaban' => 'required_if:tipe_soal,benar_salah_tabel|in:benar,salah',
    ]);

    $soal->update($request->only(['pertanyaan', 'tingkat_kesulitan']));

    // LOGIKA BARU UNTUK MEMPERBARUI SOAL TABEL
    if ($soal->tipe_soal == 'benar_salah_tabel') {
        // Hapus pernyataan lama, lalu buat ulang dari data form
        $soal->pernyataan()->delete();
        if ($request->has('pernyataan')) {
            foreach ($request->pernyataan as $index => $item) {
                $soal->pernyataan()->create([
                    'pernyataan' => $item['teks'],
                    'jawaban_benar' => $item['jawaban'] == 'benar' ? 1 : 0,
                    'urutan' => $index + 1,
                ]);
            }
        }
    } else { // Logika lama untuk pilihan ganda & isian
        $soal->pilihanJawaban()->delete();
        if ($request->has('pilihan')) {
            foreach ($request->pilihan as $pilihanData) {
                $soal->pilihanJawaban()->create([
                    'pilihan_teks' => $pilihanData['teks'],
                    'apakah_benar' => isset($pilihanData['benar']),
                ]);
            }
        }
    }

     return redirect()->route('mata-pelajaran.soal.index', ['mata_pelajaran' => $soal->mata_pelajaran_id])
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
