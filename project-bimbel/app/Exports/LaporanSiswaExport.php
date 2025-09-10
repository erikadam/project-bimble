<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\PaketTryout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanSiswaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $paketTryout;
    protected $semuaMapelPaket;

    public function __construct(PaketTryout $paketTryout)
    {
        $this->paketTryout = $paketTryout->load('mataPelajaran');
        $this->semuaMapelPaket = $this->paketTryout->mataPelajaran->sortBy('nama_mapel');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Student::where('paket_tryout_id', $this->paketTryout->id)
                      ->with(['jawabanPeserta.soal'])
                      ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headers = [
            'Nama Siswa',
            'Kelompok',
        ];

        foreach ($this->semuaMapelPaket as $mapel) {
            $headers[] = $mapel->nama_mapel;
        }

        $headers[] = 'Total Skor (%)';

        return $headers;
    }

    /**
     * @param mixed $student
     * @return array
     */
    public function map($student): array
    {
        $jawabanSiswa = $student->jawabanPeserta;
        $jawabanPerMapel = $jawabanSiswa->groupBy('soal.mata_pelajaran_id');
        $hasilPerMapel = [];

        foreach ($jawabanPerMapel as $mapelId => $jawabanMapel) {
            $totalSoalMapel = $jawabanMapel->count();
            $totalBenarMapel = $jawabanMapel->where('apakah_benar', true)->count();
            $skorMapel = $totalSoalMapel > 0 ? ($totalBenarMapel / $totalSoalMapel) * 100 : 0;
            $hasilPerMapel[$mapelId] = $skorMapel;
        }

        $row = [
            $student->nama_lengkap,
            $student->kelompok,
        ];

        foreach ($this->semuaMapelPaket as $mapel) {
            if (isset($hasilPerMapel[$mapel->id])) {
                // --- PERBAIKAN DI SINI ---
                // Menambahkan number_format untuk memastikan dua desimal
                $row[] = number_format($hasilPerMapel[$mapel->id], 2);
            } else {
                $row[] = 'Tidak dikerjakan';
            }
        }

        $totalSoalKeseluruhan = $jawabanSiswa->count();
        $totalBenarKeseluruhan = $jawabanSiswa->where('apakah_benar', true)->count();
        $skorTotal = $totalSoalKeseluruhan > 0 ? ($totalBenarKeseluruhan / $totalSoalKeseluruhan) * 100 : 0;
        $row[] = number_format($skorTotal, 2);

        return $row;
    }
}
