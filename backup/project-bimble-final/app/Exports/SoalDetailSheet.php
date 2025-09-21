<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\PaketTryout;
use App\Models\Soal;

class SoalDetailSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $paketTryout;

    public function __construct($paketTryout)
    {
        $this->paketTryout = $paketTryout;
    }

    public function collection()
    {
        $soalPilihan = $this->paketTryout->soalPilihan()->with('pilihanJawaban')->get();
        $data = collect();

        foreach ($soalPilihan->sortBy('id') as $soal) {
            $jawabanBenar = $soal->pilihanJawaban->where('apakah_benar', true)->pluck('pilihan_teks')->implode(', ');
            $data->push([
                'ID Soal' => $soal->id,
                'Pertanyaan' => strip_tags($soal->pertanyaan),
                'Jawaban Benar' => $jawabanBenar,
            ]);
        }
        return $data;
    }

    public function headings(): array
    {
        return ['ID Soal', 'Pertanyaan', 'Jawaban Benar'];
    }

    public function title(): string
    {
        return 'Detail Soal & Jawaban';
    }
}
