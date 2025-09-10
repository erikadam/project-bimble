<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{

protected $table = 'soal';
protected $fillable = ['mata_pelajaran_id', 'user_id', 'pertanyaan', 'gambar_path', 'tipe_soal', 'status'];

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
}
