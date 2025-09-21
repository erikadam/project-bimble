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
    Schema::create('paket_mapel', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paket_tryout_id')->constrained('paket_tryout')->onDelete('cascade');
        $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
        $table->integer('durasi_menit')->comment('Durasi pengerjaan per mata pelajaran');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_mapel');
    }
};
