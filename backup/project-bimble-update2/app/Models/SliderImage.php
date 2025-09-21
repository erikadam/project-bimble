<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional tapi praktik yang baik)
    protected $table = 'slider_images';

    // Mendefinisikan kolom yang boleh diisi secara massal
    protected $fillable = ['path'];
}
