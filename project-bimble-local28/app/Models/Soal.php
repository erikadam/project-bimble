<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'mata_pelajaran_id',
        'pertanyaan',
        'tipe_soal',
        'status',
        'gambar_path',
        'parent_id',
        'kesulitan',
        'pilihan_kompleks',
        'user_id' // Pastikan user_id juga ada di fillable
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function pilihanJawaban()
    {
        return $this->hasMany(PilihanJawaban::class);
    }

    public function paketTryout()
    {
        return $this->belongsToMany(PaketTryout::class, 'paket_tryout_soal');
    }

    public function ulangans()
    {
        return $this->belongsToMany(Ulangan::class, 'ulangan_soal');
    }

    // Relasi yang hilang, yang menyebabkan error
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pernyataans(): HasMany
    {
        return $this->hasMany(SoalPernyataan::class, 'soal_id');
    }
public function pernyataan()
    {
        // Pastikan nama Model 'SoalPernyataan' sudah benar
        return $this->hasMany(SoalPernyataan::class);
    }
}
