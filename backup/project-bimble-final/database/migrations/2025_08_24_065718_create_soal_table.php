<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/..._create_soal_table.php
public function up(): void
{
    Schema::create('soal', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Guru yang membuat
        $table->text('pertanyaan');
        $table->enum('tipe_soal', ['pilihan_ganda', 'isian']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
