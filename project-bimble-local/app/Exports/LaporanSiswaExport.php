<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\PaketTryout;
use App\Models\Student;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;
use App\Exports\RingkasanSiswaSheet;
use App\Exports\JawabanPerMapelSheet;

class LaporanSiswaExport implements WithMultipleSheets
{
    protected $paketTryoutId;
    protected $students;
    protected $mataPelajarans;
    protected $paketTryout;

    public function __construct(int $paketTryoutId)
    {
        $this->paketTryout = PaketTryout::with('mataPelajaran', 'soalPilihan.pilihanJawaban')->findOrFail($paketTryoutId);
        $this->mataPelajarans = $this->paketTryout->mataPelajaran()->orderBy('nama_mapel', 'asc')->get();
        $this->students = Student::where('paket_tryout_id', $paketTryoutId)
                                 ->with('jawabanPeserta.soal.pilihanJawaban')
                                 ->get();
    }

    public function sheets(): array
    {
        $sheets = [];

        // Sheet pertama: Ringkasan Nilai Siswa
        $sheets[] = new RingkasanSiswaSheet($this->paketTryout, $this->students);

        // Sheet selanjutnya: Detail Jawaban per Mata Pelajaran
        foreach ($this->mataPelajarans as $mapel) {
            $sheets[] = new JawabanPerMapelSheet($this->paketTryout, $this->students, $mapel);
        }

        return $sheets;
    }
}
