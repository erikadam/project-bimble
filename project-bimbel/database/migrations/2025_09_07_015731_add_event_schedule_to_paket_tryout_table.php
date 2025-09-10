<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modifikasi tabel paket_tryout
        Schema::table('paket_tryout', function (Blueprint $table) {
            // Ubah tipe_paket untuk memasukkan 'event'
            $table->enum('tipe_paket', ['tryout', 'ulangan', 'event'])->default('tryout')->change();
            // Tambahkan kolom untuk waktu mulai event
            $table->dateTime('waktu_mulai')->nullable()->after('durasi_menit');
        });

        // 2. Tambahkan kolom urutan di tabel pivot paket_mapel
        Schema::table('paket_mapel', function (Blueprint $table) {
            $table->integer('urutan')->default(0)->after('mata_pelajaran_id');
        });
    }

    public function down(): void
    {
        Schema::table('paket_tryout', function (Blueprint $table) {
            $table->enum('tipe_paket', ['tryout', 'ulangan'])->default('tryout')->change();
            $table->dropColumn('waktu_mulai');
        });

        Schema::table('paket_mapel', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
