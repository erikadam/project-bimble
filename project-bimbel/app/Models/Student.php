<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'paket_tryout_id',
        'nama_lengkap',
        'jenjang_pendidikan',
        'kelompok',
        'session_id',
        'kelas',
        'asal_sekolah',
    ];
        public function jawabanPeserta()
    {
        return $this->hasMany(JawabanPeserta::class);
    }
        public function paketTryout()
    {
        return $this->belongsTo(PaketTryout::class);
    }
}
