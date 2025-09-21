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
    Schema::create('paket_tryout_soal', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paket_tryout_id')->constrained('paket_tryout')->onDelete('cascade');
        $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_tryout_soal');
    }
};
