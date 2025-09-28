<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    /**
     * Tampilkan halaman untuk memilih jenjang pendidikan.
     */
    public function pilihJenjang()
    {
        return view('mata-pelajaran.pilih-jenjang');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $jenjang = $request->get('jenjang');
        $kelas = $request->get('kelas');
        // Tambahkan ->with('guru') untuk mengambil data guru secara efisien
        $query = MataPelajaran::withCount('soal')->with('guru');

        if ($jenjang) {
            $query->where('jenjang_pendidikan', $jenjang);
        }
        if ($kelas) {
            $query->where('kelas', $kelas);
        }
        $mataPelajaran = $query->orderBy('jenjang_pendidikan')->orderBy('kelas')->orderBy('nama_mapel')->get();
        return view('mata-pelajaran.index', compact('mataPelajaran', 'jenjang', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tidak kita gunakan, form ada di halaman index
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
            'kelas' => 'required|integer|min:1|max:12',
            'is_wajib' => 'required|boolean',
        ]);

        $validatedData['guru_id'] = auth()->id();
        MataPelajaran::create($validatedData);

        return redirect()->route('mata-pelajaran.index', ['jenjang' => $validatedData['jenjang_pendidikan']])
                         ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaran $mataPelajaran)
    {
        // Tidak kita gunakan saat ini
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('mata-pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validatedData = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
            'kelas' => 'required|integer|min:1|max:12',
            'is_wajib' => 'required|boolean',
        ]);

        $mataPelajaran->update($validatedData);

        return redirect()->route('mata-pelajaran.index', ['jenjang' => $validatedData['jenjang_pendidikan']])
                         ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $jenjang = $mataPelajaran->jenjang_pendidikan;
        if ($mataPelajaran->soal()->count() > 0) {
            return redirect()->route('mata-pelajaran.index', ['jenjang' => $jenjang])
                             ->with('error', 'Gagal! Hapus dulu semua soal di dalam mata pelajaran ini.');
        }
        $mataPelajaran->delete();
        return redirect()->route('mata-pelajaran.index', ['jenjang' => $jenjang])
                         ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
