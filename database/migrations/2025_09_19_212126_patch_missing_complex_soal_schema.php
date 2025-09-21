<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambal schema.
     */
    public function up(): void
    {
        // 1. Menambahkan kolom 'pilihan_kompleks' ke tabel 'soal' jika belum ada.
        if (!Schema::hasColumn('soal', 'pilihan_kompleks')) {
            Schema::table('soal', function (Blueprint $table) {
                $table->json('pilihan_kompleks')->nullable()->after('tipe_soal');
            });
        }

        // 2. Membuat tabel 'soal_pernyataans' jika belum ada.
        if (!Schema::hasTable('soal_pernyataans')) {
            Schema::create('soal_pernyataans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
                $table->text('pernyataan_teks');
                $table->timestamps();
            });
        }

        // 3. Membuat tabel 'pernyataan_pilihan_jawaban' jika belum ada.
        //    Struktur ini 100% cocok dengan database lokal Anda.
        if (!Schema::hasTable('pernyataan_pilihan_jawaban')) {
            Schema::create('pernyataan_pilihan_jawaban', function (Blueprint $table) {
                $table->id(); // Membuat kolom `id`
                $table->foreignId('soal_pernyataan_id')->constrained('soal_pernyataans')->onDelete('cascade'); // Membuat kolom `soal_pernyataan_id`
                $table->text('pilihan_teks'); // Membuat kolom `pilihan_teks`
                $table->boolean('apakah_benar')->default(false); // Membuat kolom `apakah_benar`
                $table->timestamps(); // Membuat kolom `created_at` dan `updated_at`
            });
        }
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_pilihan_jawaban');
        Schema::dropIfExists('soal_pernyataans');
        if (Schema::hasColumn('soal', 'pilihan_kompleks')) {
            Schema::table('soal', function (Blueprint $table) {
                $table->dropColumn('pilihan_kompleks');
            });
        }
    }
};
