<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPernyataan extends Model
{
    use HasFactory;

    protected $table = 'soal_pernyataan';

    protected $fillable = [
        'soal_id',
        'pernyataan',
        'jawaban_benar',
        'urutan',
    ];

    /**
     * Mendefinisikan bahwa setiap pernyataan dimiliki oleh satu soal.
     */
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
