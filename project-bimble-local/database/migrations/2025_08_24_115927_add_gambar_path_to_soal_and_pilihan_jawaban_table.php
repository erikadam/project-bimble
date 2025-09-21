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
            $table->string('gambar_path')->nullable()->after('pertanyaan');
        });
        Schema::table('pilihan_jawaban', function (Blueprint $table) {
            $table->string('gambar_path')->nullable()->after('pilihan_teks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('gambar_path');
        });
        Schema::table('pilihan_jawaban', function (Blueprint $table) {
            $table->dropColumn('gambar_path');
        });
    }
};
