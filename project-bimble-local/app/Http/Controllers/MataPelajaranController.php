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

        $mataPelajaran = MataPelajaran::when($jenjang, function ($query, $jenjang) {
            return $query->where('jenjang_pendidikan', $jenjang);
        })
            ->orderBy('jenjang_pendidikan', 'asc')
            ->orderBy('is_wajib', 'desc')
            ->orderBy('nama_mapel', 'asc')
            ->get();

        $jenjangs = MataPelajaran::select('jenjang_pendidikan')->distinct()->get();

        return view('mata-pelajaran.index', compact('mataPelajaran', 'jenjang', 'jenjangs'));
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
        $request->validate([
            'nama_mapel' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mata_pelajaran')->where(function ($query) use ($request) {
                    return $query->where('jenjang_pendidikan', $request->jenjang_pendidikan);
                }),
            ],
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
            'is_wajib' => 'required|boolean',
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
            'jenjang_pendidikan' => $request->jenjang_pendidikan,
            'is_wajib' => $request->is_wajib,
        ]);

        return redirect()->route('mata-pelajaran.index', ['jenjang' => $request->jenjang_pendidikan])->with('success', 'Mata pelajaran berhasil ditambahkan.');
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
        $request->validate([
            'nama_mapel' => [
                'required',
                'string',
                'max:255',
                Rule::unique('mata_pelajaran')
                    ->where(function ($query) use ($request) {
                        return $query->where('jenjang_pendidikan', $request->jenjang_pendidikan);
                    })
                    ->ignore($mataPelajaran->id),
            ],
            'jenjang_pendidikan' => 'required|string|in:SD,SMP,SMA',
        ]);

        $mataPelajaran->update([
            'nama_mapel' => $request->nama_mapel,
            'jenjang_pendidikan' => $request->jenjang_pendidikan,
        ]);

        return redirect()->route('mata-pelajaran.index', ['jenjang' => $request->jenjang_pendidikan])->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();

        return redirect()->route('mata-pelajaran.index', ['jenjang' => $mataPelajaran->jenjang_pendidikan])->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
