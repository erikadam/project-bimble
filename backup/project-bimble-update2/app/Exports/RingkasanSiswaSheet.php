<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\PaketTryout;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class RingkasanSiswaSheet implements FromCollection, WithTitle, WithEvents
{
    protected $paketTryout;
    protected $students;
    protected $mataPelajarans;
    protected $bobotSoalMap;

    public function __construct($paketTryout, $students)
    {
        $this->paketTryout = $paketTryout;
        $this->students = $students;
        $this->mataPelajarans = $paketTryout->mataPelajaran()->orderBy('nama_mapel', 'asc')->get();
        $this->bobotSoalMap = DB::table('paket_tryout_soal')
                                ->where('paket_tryout_id', $paketTryout->id)
                                ->pluck('bobot', 'soal_id');
    }

    public function collection()
    {
        $data = collect();

        for ($i = 0; $i < 5; $i++) {
            $data->push(['']);
        }

        $studentsWithScores = $this->students->map(function ($student) {
            $totalSkorBobotDiperoleh = 0;
            $totalSkorBobotMaksimal = 0;

            foreach ($this->mataPelajarans as $mapel) {
                $jawabanMapel = $student->jawabanPeserta->filter(fn($j) => $j->soal && $j->soal->mata_pelajaran_id == $mapel->id);
                $soalIdsDiMapel = $this->paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapel->id)->pluck('soal_id');
                $skorDiperolehMapel = 0;

                foreach ($jawabanMapel->where('apakah_benar', true) as $j) {
                    $skorDiperolehMapel += $this->bobotSoalMap[$j->soal_id] ?? 1;
                }

                $skorMaksimalMapel = $soalIdsDiMapel->sum(fn($soalId) => $this->bobotSoalMap[$soalId] ?? 1);
                $totalSkorBobotDiperoleh += $skorDiperolehMapel;
                $totalSkorBobotMaksimal += $skorMaksimalMapel;
            }
            $student->skor_total = ($totalSkorBobotMaksimal > 0) ? ($totalSkorBobotDiperoleh / $totalSkorBobotMaksimal) * 100 : 0;
            return $student;
        })->sortByDesc('skor_total');

        foreach ($studentsWithScores as $index => $student) {
            $row = [
                'Peringkat' => $index + 1, // Perbaikan pada peringkat
                'NAMA LENGKAP' => $student->nama_lengkap,
                'Sekolah' => $student->asal_sekolah,
                'Kelas' => $student->kelas,
                'Kelompok' => $student->kelompok,
            ];

            foreach ($this->mataPelajarans as $mapel) {
                $jawabanMapel = $student->jawabanPeserta->filter(fn($j) => $j->soal && $j->soal->mata_pelajaran_id == $mapel->id);
                $benar = $jawabanMapel->where('apakah_benar', true)->count();
                $salah = $jawabanMapel->count() - $benar;
                $row[] = $benar;
                $row[] = $salah;

                $skorDiperolehMapel = 0;
                $soalIdsDiMapel = $this->paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapel->id)->pluck('soal_id');
                foreach ($jawabanMapel->where('apakah_benar', true) as $j) {
                    $skorDiperolehMapel += $this->bobotSoalMap[$j->soal_id] ?? 1;
                }
                $skorMaksimalMapel = $soalIdsDiMapel->sum(fn($soalId) => $this->bobotSoalMap[$soalId] ?? 1);
                $nilai = ($skorMaksimalMapel > 0) ? ($skorDiperolehMapel / $skorMaksimalMapel) * 100 : 0;

                $row[] = number_format($nilai, 2);
            }
            $row[] = number_format($student->skor_total, 2);
            $data->push($row);
        }

        $maxScores = ['Nilai maksimum', '', '', '', ''];
        $totalMaxScore = 0;
        foreach ($this->mataPelajarans as $mapel) {
            $soalIdsDiMapel = $this->paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapel->id)->pluck('soal_id');
            $jumlahSoal = $soalIdsDiMapel->count();
            $maxScores[] = $jumlahSoal;
            $maxScores[] = 0;
            $skorMaksimalMapel = $soalIdsDiMapel->sum(fn($soalId) => $this->bobotSoalMap[$soalId] ?? 1);
            $maxScores[] = $skorMaksimalMapel;
            $totalMaxScore += $skorMaksimalMapel;
        }
        $maxScores[] = $totalMaxScore;
        $data->push($maxScores);

        return $data;
    }

    public function title(): string
    {
        return 'HASIL TRYOUT 1 UTBK 2025';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->setCellValue('A1', $this->paketTryout->nama_paket . ' - ' . $this->paketTryout->mataPelajaran->first()->jenjang_pendidikan);
                $sheet->setCellValue('A2', 'Tanggal: ' . $this->paketTryout->created_at->format('d-m-Y H:i:s'));
                $sheet->setCellValue('A3', 'Deskripsi: ' . $this->paketTryout->deskripsi);

                $numStaticHeaders = 5;
                $numColsPerMapel = 3;
                $lastColumnIndex = $numStaticHeaders + ($this->mataPelajarans->count() * $numColsPerMapel) + 1;
                $lastColumnLetter = Coordinate::stringFromColumnIndex($lastColumnIndex);

                $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1:' . $lastColumnLetter . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $startRow = 6;
                $currentColumn = 1;

                $staticHeaders = ['Peringkat', 'NAMA LENGKAP', 'Sekolah', 'Kelas', 'Kelompok'];
                foreach ($staticHeaders as $header) {
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumn) . $startRow, $header);
                    $sheet->mergeCells(Coordinate::stringFromColumnIndex($currentColumn) . $startRow . ':' . Coordinate::stringFromColumnIndex($currentColumn) . ($startRow + 1));
                    $currentColumn++;
                }

                $mapelHeaderStartCol = $currentColumn;
                foreach ($this->mataPelajarans as $mapel) {
                    $startMergeColumn = $currentColumn;
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($startMergeColumn) . $startRow, $mapel->nama_mapel);
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($currentColumn) . ($startRow + 1), 'Benar');
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$currentColumn) . ($startRow + 1), 'Salah');
                    $sheet->setCellValue(Coordinate::stringFromColumnIndex(++$currentColumn) . ($startRow + 1), 'Nilai');
                    $endMergeColumn = $currentColumn;
                    $sheet->mergeCells(Coordinate::stringFromColumnIndex($startMergeColumn) . $startRow . ':' . Coordinate::stringFromColumnIndex($endMergeColumn) . $startRow);
                    $currentColumn++;
                }

                $skorStartCol = $currentColumn;
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($skorStartCol) . $startRow, 'SKOR');
                $sheet->mergeCells(Coordinate::stringFromColumnIndex($skorStartCol) . $startRow . ':' . Coordinate::stringFromColumnIndex($skorStartCol) . ($startRow + 1));

                $lastColumnIndex = $currentColumn;
                $lastColumnLetter = Coordinate::stringFromColumnIndex($lastColumnIndex);
                $lastRow = $sheet->getHighestRow();

                $headerRange = 'A' . $startRow . ':' . $lastColumnLetter . ($startRow + 1);
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D9D9D9']],
                ]);

                $tableRange = 'A' . ($startRow + 2) . ':' . $lastColumnLetter . $lastRow;
                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);

                for ($i = 1; $i <= $lastColumnIndex; $i++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
                }
            },
        ];
    }
}
