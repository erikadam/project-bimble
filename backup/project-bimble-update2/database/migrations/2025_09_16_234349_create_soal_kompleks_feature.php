<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     *
     * @return void
     */
    public function up(): void
    {
        // LANGKAH 1: Perbaiki data yang tidak konsisten SEBELUM mengubah struktur.
        // Ini akan mengubah 'Pilihan Ganda Kompleks' menjadi 'pilihan_ganda_kompleks'
        DB::table('soal')
            ->where('tipe_soal', 'Pilihan Ganda Kompleks')
            ->update(['tipe_soal' => 'pilihan_ganda_kompleks']);

        // LANGKAH 2: Ubah kolom ENUM dengan cara yang aman.
        // Daftar ENUM ini sudah mencakup semua tipe soal yang ada di database Anda.
        DB::statement("ALTER TABLE soal CHANGE COLUMN tipe_soal tipe_soal ENUM('pilihan_ganda', 'isian', 'pilihan_ganda_majemuk', 'pilihan_ganda_kompleks') NOT NULL");

        // LANGKAH 3: Tambahkan kolom `parent_id` untuk relasi soal.
        Schema::table('soal', function (Blueprint $table) {
            if (!Schema::hasColumn('soal', 'parent_id')) {
                // onDelete('cascade') agar sub-soal ikut terhapus jika soal induk dihapus.
                $table->foreignId('parent_id')->nullable()->constrained('soal')->onDelete('cascade');
            }
        });

        // LANGKAH 4: Buat tabel 'soal_pernyataans' dengan nama kolom yang konsisten.
        Schema::create('soal_pernyataans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
            $table->text('pernyataan_teks'); // Menggunakan nama yang lebih deskriptif
            $table->timestamps();
        });

        // LANGKAH 5: Buat tabel 'pernyataan_pilihan_jawaban' dengan struktur yang BENAR.
        Schema::create('pernyataan_pilihan_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_pernyataan_id')->constrained('soal_pernyataans')->onDelete('cascade');
            $table->text('pilihan_teks');
            $table->boolean('apakah_benar')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_pilihan_jawaban');
        Schema::dropIfExists('soal_pernyataans');

        if (Schema::hasColumn('soal', 'parent_id')) {
             Schema::table('soal', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }

        // Mengembalikan ENUM ke state sebelumnya
        DB::statement("ALTER TABLE soal CHANGE COLUMN tipe_soal tipe_soal ENUM('pilihan_ganda', 'isian', 'pilihan_ganda_majemuk') NOT NULL");
    }
};
