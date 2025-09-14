<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanSiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;
    protected $mataPelajaran;

    // Constructor menerima daftar siswa dan daftar mapel
    public function __construct($students, $mataPelajaran)
    {
        $this->students = $students;
        $this->mataPelajaran = $mataPelajaran;
    }

    public function collection()
    {
        return $this->students;
    }

    /**
     * Membuat judul kolom (header) secara dinamis.
     */
    public function headings(): array
    {
        // Header statis di bagian awal
        $headings = [
            'Nama Lengkap', 'Kelas', 'Asal Sekolah', 'Jenjang', 'Kelompok', 'Waktu Selesai',
        ];

        // Tambahkan nama setiap mata pelajaran sebagai header
        foreach ($this->mataPelajaran as $mapel) {
            $headings[] = $mapel->nama_mapel;
        }

        // Header statis di bagian akhir
        $headings = array_merge($headings, [
            'Jumlah Benar', 'Jumlah Salah', 'Skor Akhir',
        ]);

        return $headings;
    }

    /**
     * Memetakan data setiap siswa ke baris Excel secara dinamis.
     */
    public function map($student): array
    {
        // --- AWAL PERUBAHAN LOGIKA ---

        // Data statis siswa di bagian awal
        $row = [
            $student->nama_lengkap ?? '-',
            $student->kelas ?? '-',
            $student->asal_sekolah ?? '-',
            $student->jenjang_pendidikan ?? '-',
            $student->kelompok ?? '-',
            $student->updated_at ? $student->updated_at->format('d M Y, H:i') : '-',
        ];

        // Loop melalui daftar mapel yang seharusnya ada di paket tryout
        foreach ($this->mataPelajaran as $mapel) {

            // Filter jawaban siswa hanya untuk mata pelajaran saat ini
            $jawabanUntukMapelIni = $student->jawabanPeserta->filter(function ($jawaban) use ($mapel) {
                // Pastikan jawaban terhubung ke soal, DAN soal tersebut milik mapel ini
                return $jawaban->soal && $jawaban->soal->mata_pelajaran_id == $mapel->id;
            });

            // Hitung skor (jawaban benar) dari hasil filter di atas
            $skorMapel = $jawabanUntukMapelIni->where('apakah_benar', true)->count();

            $row[] = $skorMapel;
        }

        // Hitung skor total dari semua jawaban yang dimiliki siswa
        $jumlahBenar = $student->jawabanPeserta->where('apakah_benar', true)->count();
        $jumlahSalah = $student->jawabanPeserta->where('apakah_benar', false)->count();

        // Data statis di bagian akhir
        $row = array_merge($row, [
            $jumlahBenar,
            $jumlahSalah,
            $jumlahBenar, // Skor total
        ]);

        return $row;
        // --- AKHIR PERUBAHAN LOGIKA ---
    }
}
