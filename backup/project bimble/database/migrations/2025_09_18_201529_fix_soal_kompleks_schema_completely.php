<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     */
    public function up(): void
    {
        // 1. Menyesuaikan tabel `soal` sesuai Bukti A.
        DB::statement("ALTER TABLE soal MODIFY COLUMN tipe_soal ENUM('pilihan_ganda', 'isian', 'pilihan_ganda_majemuk', 'benar_salah_tabel', 'pilihan_ganda_kompleks') NOT NULL");
        Schema::table('soal', function (Blueprint $table) {
            if (!Schema::hasColumn('soal', 'pilihan_kompleks')) {
                $table->json('pilihan_kompleks')->nullable()->after('tipe_soal');
            }
        });

        // 2. Membuat tabel `soal_pernyataans` sesuai Bukti B.
        if (!Schema::hasTable('soal_pernyataans')) {
            Schema::create('soal_pernyataans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
                $table->text('pernyataan_teks');
                $table->boolean('jawaban_benar')->nullable();
                $table->string('jawaban_teks')->nullable();
                $table->timestamps();
            });
        }

        // 3. Membuat tabel `pernyataan_pilihan_jawaban` sesuai Bukti C.
        if (!Schema::hasTable('pernyataan_pilihan_jawaban')) {
            Schema::create('pernyataan_pilihan_jawaban', function (Blueprint $table) {
                $table->id();
                $table->foreignId('soal_pernyataan_id')->constrained('soal_pernyataans')->onDelete('cascade');
                $table->text('pilihan_teks'); // Sesuai dengan query INSERT
                $table->boolean('apakah_benar')->default(false); // Sesuai dengan query INSERT
                $table->timestamps();
            });
        }
    }

    /**
     * Membatalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernyataan_pilihan_jawaban');
        Schema::dropIfExists('soal_pernyataans');

        Schema::table('soal', function (Blueprint $table) {
            if (Schema::hasColumn('soal', 'pilihan_kompleks')) {
                $table->dropColumn('pilihan_kompleks');
            }
        });

        DB::statement("ALTER TABLE soal MODIFY COLUMN tipe_soal ENUM('pilihan_ganda', 'isian', 'pilihan_ganda_majemuk') NOT NULL");
    }
};
