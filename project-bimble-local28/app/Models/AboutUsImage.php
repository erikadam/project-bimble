<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsImage extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     */
    protected $table = 'about_us_images';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'about_us_id',
        'image_path',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model AboutUs.
     * Setiap gambar dimiliki oleh satu entitas AboutUs.
     */
    public function aboutUs()
    {
        return $this->belongsTo(AboutUs::class);
    }
}
