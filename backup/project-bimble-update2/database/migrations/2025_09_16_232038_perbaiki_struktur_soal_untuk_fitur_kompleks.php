<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Memperbaiki tabel 'soal'
        Schema::table('soal', function (Blueprint $table) {
            // Mengubah tipe enum untuk menambahkan tipe soal baru
            $table->enum('tipe_soal', [
                'pilihan_ganda',
                'isian',
                'pilihan_ganda_majemuk',
                'benar_salah_tabel',
                'pilihan_ganda_kompleks' // Menambahkan tipe baru
            ])->change();

            // Menambahkan kolom untuk menyimpan judul kolom dinamis
            $table->json('opsi_kolom')->nullable()->after('tipe_soal');
        });

        // 2. Memperbaiki tabel 'soal_pernyataan'
        Schema::table('soal_pernyataan', function (Blueprint $table) {
            // Menambahkan kolom untuk menyimpan jawaban teks dari soal matriks
            $table->string('jawaban_teks')->nullable()->after('jawaban_benar');
        });

        // 3. Membuat tabel perantara untuk Pilihan Ganda Kompleks
        Schema::create('pernyataan_pilihan_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_pernyataan_id')->constrained('soal_pernyataan')->onDelete('cascade');
            $table->foreignId('pilihan_jawaban_id')->constrained('pilihan_jawaban')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pernyataan_pilihan_jawaban');

        Schema::table('soal_pernyataan', function (Blueprint $table) {
            $table->dropColumn('jawaban_teks');
        });

        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('opsi_kolom');
            $table->enum('tipe_soal', [
                'pilihan_ganda',
                'isian',
                'pilihan_ganda_majemuk',
                'benar_salah_tabel'
            ])->change();
        });
    }
};
