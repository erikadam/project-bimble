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
        Schema::table('soal', function (Blueprint $table) {
            // Mengubah tipe enum untuk menambahkan 'pilihan_ganda_majemuk'
            $table->enum('tipe_soal', ['pilihan_ganda', 'isian', 'pilihan_ganda_majemuk'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            // Mengembalikan ke tipe enum semula jika migrasi di-rollback
            $table->enum('tipe_soal', ['pilihan_ganda', 'isian'])->change();
        });
    }
};
