<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\PaketTryout;
use App\Models\Student;
use App\Models\MataPelajaran;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class JawabanPerMapelSheet implements FromCollection, WithTitle, WithHeadings, WithEvents
{
    protected $paketTryout;
    protected $students;
    protected $mapel;
    protected $soalPilihan;

    public function __construct($paketTryout, $students, MataPelajaran $mapel)
    {
        $this->paketTryout = $paketTryout;
        $this->students = $students;
        $this->mapel = $mapel;
        $this->soalPilihan = $paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapel->id)->with('pilihanJawaban')->get()->sortBy('id');
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->students as $student) {
            $row = [
                'NAMA LENGKAP' => $student->nama_lengkap,
                'Asal Sekolah (lengkap)' => $student->asal_sekolah,
                'Kelas' => $student->kelas,
                'Kelompok' => $student->kelompok,
            ];

            foreach ($this->soalPilihan as $soal) {
                $jawabanSiswa = $student->jawabanPeserta->firstWhere('soal_id', $soal->id);
                $status = 'N/A';
                if ($jawabanSiswa) {
                    if ($jawabanSiswa->apakah_benar) {
                        $status = 'Benar';
                    } else {
                        $status = 'Salah-' . $jawabanSiswa->jawaban_peserta;
                    }
                }
                $row['Nomor ' . $soal->id] = $status;
            }
            $data->push($row);
        }

        return $data;
    }

    public function headings(): array
    {
        $headings = [
            'NAMA LENGKAP',
            'Asal Sekolah (lengkap)',
            'Kelas',
            'Kelompok'
        ];

        foreach ($this->soalPilihan as $soal) {
            $headings[] = 'No. ' . ($soal->nomor_soal ?? $soal->id) . ' - ' . strip_tags($soal->pertanyaan);
        }

        return $headings;
    }

    public function title(): string
    {
        return $this->mapel->nama_mapel;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = Coordinate::stringFromColumnIndex(count($this->headings()));
                $lastRow = $sheet->getHighestRow();

                $headerRange = 'A1:' . $lastColumn . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']],
                ]);

                for ($i = 'A'; $i <= $lastColumn; $i++) {
                    $sheet->getColumnDimension($i)->setAutoSize(true);
                }
            },
        ];
    }
}
