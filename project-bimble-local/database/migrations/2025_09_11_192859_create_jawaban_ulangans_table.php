<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_ulangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ulangan_session_id')->constrained('ulangan_sessions')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
            $table->foreignId('pilihan_jawaban_id')->nullable()->constrained('pilihan_jawaban')->onDelete('set null');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_ulangans');
    }
};
