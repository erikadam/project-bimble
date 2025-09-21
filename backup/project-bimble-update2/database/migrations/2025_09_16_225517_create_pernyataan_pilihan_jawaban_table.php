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
        Schema::create('pernyataan_pilihan_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_pernyataan_id')->constrained('soal_pernyataans')->onDelete('cascade');
            $table->foreignId('pilihan_jawaban_id')->constrained('pilihan_jawaban')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pernyataan_pilihan_jawaban');
    }
};
