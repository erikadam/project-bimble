<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulangan_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ulangan_id')->constrained('ulangans')->onDelete('cascade');
            $table->string('nama_siswa');
            $table->string('kelas');
            $table->string('asal_sekolah');
            $table->string('jenjang');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('jumlah_benar')->nullable();
            $table->integer('jumlah_salah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulangan_sessions');
    }
};
