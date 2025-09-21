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
    Schema::create('jawaban_pesertas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paket_tryout_id')->constrained('paket_tryout')->onDelete('cascade');
        $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
        $table->string('jawaban_peserta');
        $table->integer('waktu_pengerjaan')->nullable();
        $table->boolean('apakah_benar')->default(false);
        $table->string('session_id');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_pesertas');
    }
};
