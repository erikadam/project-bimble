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
        // 1. Menambahkan kolom baru ke tabel paket_tryout
        Schema::table('paket_tryout', function (Blueprint $table) {
            $table->integer('durasi_istirahat_wajib')->nullable()->default(0)->after('max_opsional');
        });

        // 2. Menghapus kolom lama dari tabel pivot paket_mapel
        Schema::table('paket_mapel', function (Blueprint $table) {
            $table->dropColumn('durasi_istirahat_setelah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan kolom lama
        Schema::table('paket_mapel', function (Blueprint $table) {
            $table->integer('durasi_istirahat_setelah')->nullable()->default(0);
        });

        // Menghapus kolom baru
        Schema::table('paket_tryout', function (Blueprint $table) {
            $table->dropColumn('durasi_istirahat_wajib');
        });
    }
};
