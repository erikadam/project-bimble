<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PernyataanPilihanJawaban extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'pernyataan_pilihan_jawaban';

    protected $fillable = [
        'soal_pernyataan_id',
        'pilihan_teks',
        'apakah_benar',
    ];

    /**
     * Relasi untuk mendapatkan pernyataan (induk) dari pilihan jawaban ini.
     */
    public function soalPernyataan(): BelongsTo
    {
        return $this->belongsTo(SoalPernyataan::class, 'soal_pernyataan_id');
    }
}
