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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class JawabanDetailSheet implements FromCollection, WithTitle, WithHeadings, WithEvents
{
    protected $students;
    protected $soalPilihan;

    public function __construct($paketTryout, $students)
    {
        $this->soalPilihan = $paketTryout->soalPilihan()->with('pilihanJawaban')->get()->sortBy('id');
        $this->students = $students;
    }

    public function collection()
    {
        $data = collect();

        $currentCollection = collect();
        $soalPilihan = $this->soalPilihan;

        foreach ($this->students as $student) {
            $row = [
                'nama_lengkap' => $student->nama_lengkap,
                'asal_sekolah' => $student->asal_sekolah,
                'jurusan_ptn_tujuan' => $student->jurusan . ' - ' . $student->universitas_tujuan,
            ];

            foreach ($soalPilihan as $soal) {
                $jawabanSiswa = $student->jawabanPeserta->firstWhere('soal_id', $soal->id);
                $status = $jawabanSiswa ? ($jawabanSiswa->apakah_benar ? 'Benar' : 'Salah') : 'Tidak Dijawab';
                $row['Nomor ' . $soal->id] = $status;
            }
            $currentCollection->push($row);
        }

        return $currentCollection;
    }

    public function headings(): array
    {
        $headings = [
            'NAMA LENGKAP',
            'Asal Sekolah (lengkap)',
            'JURUSAN - PTN TUJUAN (1 pilihan)'
        ];

        foreach ($this->soalPilihan as $soal) {
            $headings[] = 'Nomor ' . ($soal->nomor_soal ?? $soal->id) . ' - ' . strip_tags($soal->pertanyaan);
        }

        return $headings;
    }

    public function title(): string
    {
        return 'Jawaban Siswa';
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
