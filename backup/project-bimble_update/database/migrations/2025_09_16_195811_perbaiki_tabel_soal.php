<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambah kolom untuk Pilihan (kolom) di tabel soal utama
        Schema::table('soal', function (Blueprint $table) {
            $table->json('pilihan_kompleks')->nullable()->after('pertanyaan');
        });

        // Membuat tabel untuk baris/pernyataan
        Schema::create('soal_pernyataans', function (Blueprint $table) {
            $table->id();

            // --- PERBAIKAN DI SINI ---
            // Secara eksplisit memberitahu Laravel untuk terhubung ke tabel 'soal'
            $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');

            $table->text('pernyataan');
            $table->string('jawaban_benar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pernyataans');

        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('pilihan_kompleks');
        });
    }
};
