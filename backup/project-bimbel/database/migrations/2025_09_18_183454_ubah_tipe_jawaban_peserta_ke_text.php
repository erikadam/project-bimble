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
        Schema::table('jawaban_pesertas', function (Blueprint $table) {
            // Periksa apakah kolom 'jawaban_peserta' ada sebelum mengubahnya
            if (Schema::hasColumn('jawaban_pesertas', 'jawaban_peserta')) {
                $table->text('jawaban_peserta')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_pesertas', function (Blueprint $table) {
            // Kembalikan tipe kolom ke string jika diperlukan
            if (Schema::hasColumn('jawaban_pesertas', 'jawaban_peserta')) {
                $table->string('jawaban_peserta')->change();
            }
        });
    }
};
