<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'mata_pelajaran';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_mapel',
        'jenjang_pendidikan',
        'is_wajib',
        'kelas',
        'guru_id',
    ];
    public function soal()
    {
        return $this->hasMany(Soal::class);
    }
    public function paketTryout()
    {
        return $this->belongsToMany(PaketTryout::class, 'paket_mapel');
    }
    public function guru()
    {
        // Relasi 'belongsTo' ke model User, menggunakan foreign key 'guru_id'
        return $this->belongsTo(User::class, 'guru_id');
    }
}
