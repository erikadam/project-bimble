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
        Schema::table('soal_pernyataans', function (Blueprint $table) {
            if (Schema::hasColumn('soal_pernyataans', 'jawaban_benar')) {
                $table->string('jawaban_benar')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_pernyataans', function (Blueprint $table) {
            if (Schema::hasColumn('soal_pernyataans', 'jawaban_benar')) {
                $table->string('jawaban_benar')->nullable(false)->change();
            }
        });
    }
};
