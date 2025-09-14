<?php

namespace App\Exports;

use App\Models\PaketTryout;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanSiswaExport implements WithEvents
{
    protected $paketTryout;
    protected $mataPelajarans;
    protected $students;
    protected $bobotSoalMap;

    public function __construct(int $paketTryoutId)
    {
        $this->paketTryout = PaketTryout::with('mataPelajaran')->findOrFail($paketTryoutId);
        $this->mataPelajarans = $this->paketTryout->mataPelajaran()->orderBy('nama_mapel', 'asc')->get();

        $this->students = Student::where('paket_tryout_id', $paketTryoutId)
                                 ->with('jawabanPeserta.soal')
                                 ->get();

        $this->bobotSoalMap = DB::table('paket_tryout_soal')
                                ->where('paket_tryout_id', $paketTryoutId)
                                ->pluck('bobot', 'soal_id');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // === 1. MENULIS INFO HEADER UTAMA ===
                $sheet->setCellValue('A1', 'Laporan Hasil Ujian Siswa');
                $sheet->mergeCells('A1:E1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

                $sheet->setCellValue('A3', 'Nama Tryout');
                $sheet->setCellValue('B3', ': ' . $this->paketTryout->nama_paket);
                $sheet->setCellValue('A4', 'Tipe Paket');
                $sheet->setCellValue('B4', ': ' . ucfirst($this->paketTryout->tipe_paket));
                $sheet->setCellValue('A5', 'Jenjang');
                $sheet->setCellValue('B5', ': ' . ($this->mataPelajarans->first()->jenjang_pendidikan ?? 'N/A'));

                // === 2. MEMBUAT HEADER TABEL (MENGGUNAKAN INDEKS NUMERIK) ===
                $startRow = 7;
                $currentColumnIndex = 1; // Mulai dari kolom 1 (A)

                $headers = ['No', 'Nama Siswa', 'Kelas', 'Asal Sekolah', 'Kelompok'];
                foreach ($this->mataPelajarans as $mapel) {
                    $headers[] = $mapel->nama_mapel . ' (Benar)';
                    $headers[] = $mapel->nama_mapel . ' (Salah)';
                    $headers[] = $mapel->nama_mapel . ' (Nilai)';
                }
                $headers[] = 'SKOR AKHIR (BERBOBOT)';

                // Tulis semua header ke sheet
                foreach ($headers as $header) {
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $startRow, $header);
                }
                $lastColumnIndex = $currentColumnIndex - 1;

                // === 3. MENGISI DATA SISWA ===
                $currentRow = $startRow + 1;
                foreach ($this->students as $index => $student) {
                    $currentColumnIndex = 1; // Reset untuk setiap baris siswa

                    // Data siswa
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $index + 1);
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $student->nama_lengkap);
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $student->kelas);
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $student->asal_sekolah);
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $student->kelompok);

                    $totalSkorBobotDiperoleh = 0;
                    $totalSkorBobotMaksimal = 0;

                    // Data per mapel
                    foreach ($this->mataPelajarans as $mapel) {
                        $jawabanMapel = $student->jawabanPeserta->filter(fn($j) => $j->soal && $j->soal->mata_pelajaran_id == $mapel->id);
                        $soalIdsDiMapel = $this->paketTryout->soalPilihan()->where('mata_pelajaran_id', $mapel->id)->pluck('soal_id');

                        $benar = $jawabanMapel->where('apakah_benar', true)->count();
                        $salah = $jawabanMapel->count() - $benar;

                        $skorDiperolehMapel = 0;
                        foreach ($jawabanMapel->where('apakah_benar', true) as $j) {
                            $skorDiperolehMapel += $this->bobotSoalMap[$j->soal_id] ?? 1;
                        }

                        $skorMaksimalMapel = 0;
                        foreach ($soalIdsDiMapel as $soalId) {
                            $skorMaksimalMapel += $this->bobotSoalMap[$soalId] ?? 1;
                        }

                        $nilai = ($skorMaksimalMapel > 0) ? round(($skorDiperolehMapel / $skorMaksimalMapel) * 100) : 0;

                        $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $benar);
                        $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $salah);
                        $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $nilai);

                        $totalSkorBobotDiperoleh += $skorDiperolehMapel;
                        $totalSkorBobotMaksimal += $skorMaksimalMapel;
                    }

                    // Skor akhir
                    $skorAkhir = ($totalSkorBobotMaksimal > 0) ? round(($totalSkorBobotDiperoleh / $totalSkorBobotMaksimal) * 100) : 0;
                    $sheet->setCellValueByColumnAndRow($currentColumnIndex++, $currentRow, $skorAkhir);

                    $currentRow++;
                }

                // === 4. MEMBERIKAN STYLE PADA TABEL ===
                $lastColumnLetter = Coordinate::stringFromColumnIndex($lastColumnIndex);
                $lastRow = $currentRow - 1;
                if ($lastRow < $startRow) { $lastRow = $startRow; } // Jaga-jaga jika tidak ada siswa

                $headerStyle = ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]];
                $sheet->getStyle('A'.$startRow.':'.$lastColumnLetter.$startRow)->applyFromArray($headerStyle);

                $tableRange = 'A'.$startRow.':'.$lastColumnLetter.$lastRow;
                $borderStyle = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]]];
                $sheet->getStyle($tableRange)->applyFromArray($borderStyle);

                for ($i = 1; $i <= $lastColumnIndex; $i++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
                }
            },
        ];
    }
}
