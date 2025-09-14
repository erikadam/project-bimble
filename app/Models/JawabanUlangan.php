<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanUlangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ulangan_session_id',
        'soal_id',
        'pilihan_jawaban_id',
        'is_correct',
    ];

    /**
     * Relasi ke UlanganSession: Satu jawaban milik satu sesi.
     */
    public function ulanganSession(): BelongsTo
    {
        return $this->belongsTo(UlanganSession::class);
    }

    /**
     * Relasi ke Soal: Satu jawaban merujuk pada satu Soal.
     */
    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }

    /**
     * Relasi ke PilihanJawaban: Satu jawaban merujuk pada satu PilihanJawaban.
     */
    public function pilihanJawaban(): BelongsTo
    {
        return $this->belongsTo(PilihanJawaban::class);
    }
}
