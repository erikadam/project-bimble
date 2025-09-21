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
        Schema::table('paket_tryout_soal', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi decimal dengan presisi 8 dan 2 angka di belakang koma
            $table->decimal('bobot', 8, 2)->default(1.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_tryout_soal', function (Blueprint $table) {
            // Mengembalikan ke tipe float jika migrasi di-rollback
            $table->float('bobot')->default(1)->change();
        });
    }
};
