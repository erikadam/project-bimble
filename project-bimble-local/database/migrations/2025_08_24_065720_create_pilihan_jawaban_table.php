<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_pilihan_jawaban_table.php
public function up(): void
{
    Schema::create('pilihan_jawaban', function (Blueprint $table) {
        $table->id();
        $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
        $table->text('pilihan_teks');
        $table->boolean('apakah_benar')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilihan_jawaban');
    }
};
