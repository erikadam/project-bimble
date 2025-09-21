<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Membuat tabel baru untuk menyimpan setiap baris pernyataan
        Schema::create('soal_pernyataan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
            $table->text('pernyataan'); // Kolom untuk teks pernyataan
            $table->boolean('jawaban_benar'); // Kolom untuk kunci jawaban (1=Benar, 0=Salah)
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 2. Menambahkan tipe soal baru 'benar_salah_tabel' ke tabel soal
        // Menggunakan DB::statement agar kompatibel dengan berbagai jenis database
        DB::statement("ALTER TABLE soal MODIFY COLUMN tipe_soal ENUM('pilihan_ganda', 'isian', 'pilihan_ganda_majemuk', 'benar_salah_tabel') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pernyataan');

        // Mengembalikan enum ke kondisi semula jika migration di-rollback
        DB::statement("ALTER TABLE soal MODIFY COLUMN tipe_soal ENUM('pilihan_ganda', 'isian', 'pilihan_ganda_majemuk') NOT NULL");
    }
};
