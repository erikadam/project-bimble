<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UlanganSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'ulangan_id',
        'nama_siswa',
        'nama_sekolah',
        'kelas',
        'asal_sekolah',
        'jenjang',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah_benar',
        'jumlah_salah',
    ];

    /**
     * Relasi ke Ulangan: Satu sesi milik satu Ulangan.
     */
    public function ulangan(): BelongsTo
    {
        return $this->belongsTo(Ulangan::class);
    }

    /**
     * Relasi ke JawabanUlangan: Satu sesi memiliki banyak jawaban.
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(JawabanUlangan::class);
    }
}
