<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalPernyataan extends Model
{
    use HasFactory;

    protected $table = 'soal_pernyataans';

    protected $fillable = [
        'soal_id',
        'pernyataan_teks',
    ];

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }

    public function pilihanJawabans(): HasMany
    {
        return $this->hasMany(PernyataanPilihanJawaban::class, 'soal_pernyataan_id');
    }
}
