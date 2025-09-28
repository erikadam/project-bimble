<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketTryout extends Model
{
    use HasFactory;

    protected $table = 'paket_tryout';

   protected $fillable = [
    'guru_id',
    'nama_paket',
    'tipe_paket',
    'deskripsi',
    'min_wajib',
    'max_opsional',
    'kode_soal',
    'status',
    'durasi_menit',
    'waktu_mulai',
    'durasi_istirahat_wajib',
];

    public function mataPelajaran()
    {
        return $this->belongsToMany(MataPelajaran::class, 'paket_mapel')->withPivot('durasi_menit');
    }

    public function soalPilihan()
    {
        return $this->belongsToMany(Soal::class, 'paket_tryout_soal')->withPivot('bobot');
    }
        public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
