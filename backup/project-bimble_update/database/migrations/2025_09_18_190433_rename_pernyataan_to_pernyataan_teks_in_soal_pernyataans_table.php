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
            if (Schema::hasColumn('soal_pernyataans', 'pernyataan')) {
                $table->renameColumn('pernyataan', 'pernyataan_teks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal_pernyataans', function (Blueprint $table) {
            if (Schema::hasColumn('soal_pernyataans', 'pernyataan_teks')) {
                $table->renameColumn('pernyataan_teks', 'pernyataan');
            }
        });
    }
};
