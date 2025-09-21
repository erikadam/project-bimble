<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanPeserta extends Model
{
    use HasFactory;

    protected $table = 'jawaban_pesertas';

    protected $fillable = [
        'paket_tryout_id',
        'soal_id',
        'student_id',
        'jawaban_peserta',
        'apakah_benar',
    ];


    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
