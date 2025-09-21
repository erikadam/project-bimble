<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            // Mengubah tipe kolom agar bisa menampung teks "Pilihan Ganda Kompleks"
            $table->string('tipe_soal', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            // Logika untuk mengembalikan jika perlu
        });
    }
};
