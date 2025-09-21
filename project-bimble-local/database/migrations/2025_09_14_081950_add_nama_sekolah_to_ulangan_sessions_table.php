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
    Schema::table('ulangan_sessions', function (Blueprint $table) {
        // Menambahkan kolom setelah 'email'
        $table->string('nama_sekolah')->nullable()->after('nama_siswa');
    });
}

public function down(): void
{
    Schema::table('ulangan_sessions', function (Blueprint $table) {
        $table->dropColumn('nama_sekolah');
    });
}
};
