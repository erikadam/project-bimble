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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paket_tryout_id')->constrained('paket_tryout')->onDelete('cascade');
        $table->string('nama_lengkap');
        $table->string('jenjang_pendidikan');
        $table->string('kelompok');
        $table->string('session_id')->unique(); // Menjaga session_id untuk sementara
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('students');
}
};
