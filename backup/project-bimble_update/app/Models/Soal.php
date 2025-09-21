<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{

protected $table = 'soal';
protected $fillable = ['mata_pelajaran_id', 'user_id', 'pertanyaan', 'gambar_path', 'tipe_soal', 'status', 'tingkat_kesulitan'];

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
        return $this->belongsToMany(Ulangan::class, 'ulangan_soal', 'soal_id', 'ulangan_id');
    }

    /**
     * Relasi satu-ke-banyak dengan JawabanUlangan.
     */
    public function jawabanUlangan()
    {
        return $this->hasMany(JawabanUlangan::class);
    }
    public function pernyataan()
{
    return $this->hasMany(SoalPernyataan::class, 'soal_id')->orderBy('urutan');
}
}
