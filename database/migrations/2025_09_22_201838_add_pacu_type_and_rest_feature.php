<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambah tipe 'pacu' ke kolom enum di tabel paket_tryout
        DB::statement("ALTER TABLE paket_tryout CHANGE COLUMN tipe_paket tipe_paket ENUM('tryout','ulangan','event','pacu') NOT NULL");

        // Menambah kolom untuk durasi istirahat di tabel pivot paket_mapel
        Schema::table('paket_mapel', function (Blueprint $table) {
            $table->integer('durasi_istirahat_setelah')->nullable()->default(0)->after('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan tipe enum ke kondisi semula
        DB::statement("ALTER TABLE paket_tryout CHANGE COLUMN tipe_paket tipe_paket ENUM('tryout','ulangan','event') NOT NULL");

        // Menghapus kolom durasi istirahat
        Schema::table('paket_mapel', function (Blueprint $table) {
            $table->dropColumn('durasi_istirahat_setelah');
        });
    }
};
