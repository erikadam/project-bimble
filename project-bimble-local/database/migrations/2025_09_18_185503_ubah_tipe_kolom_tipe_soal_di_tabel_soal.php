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
        Schema::table('soal', function (Blueprint $table) {
            if (Schema::hasColumn('soal', 'tipe_soal')) {
                $table->string('tipe_soal', 50)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            if (Schema::hasColumn('soal', 'tipe_soal')) {
                $table->string('tipe_soal', 20)->change(); // Ganti 20 dengan panjang yang sesuai
            }
        });
    }
};
