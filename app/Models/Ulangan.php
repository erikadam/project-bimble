<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ulangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_pelajaran_id',
        'nama_ulangan',
        'deskripsi',
        'status',
    ];

    /**
     * Relasi ke MataPelajaran: Satu Ulangan milik satu MataPelajaran.
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * Relasi many-to-many dengan model Soal melalui tabel pivot 'ulangan_soal'.
     */
    public function soal()
    {
        return $this->belongsToMany(Soal::class, 'ulangan_soal', 'ulangan_id', 'soal_id');
    }

    public function sessions()
    {
        return $this->hasMany(UlanganSession::class);
    }
}
