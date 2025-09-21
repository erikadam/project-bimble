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
    Schema::create('paket_tryout', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
        $table->string('kode_soal')->unique();
        $table->string('nama_paket');
        $table->enum('tipe_paket', ['tryout', 'ulangan'])->default('tryout');
        $table->text('deskripsi')->nullable();
        $table->integer('durasi_menit');
        $table->string('status')->default('draft');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_tryout');
    }
};
