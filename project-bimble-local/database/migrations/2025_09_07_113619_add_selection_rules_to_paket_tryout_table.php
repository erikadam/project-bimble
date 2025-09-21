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
        Schema::table('paket_tryout', function (Blueprint $table) {
            $table->integer('min_wajib')->nullable()->after('deskripsi');
            $table->integer('max_opsional')->nullable()->after('min_wajib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_tryout', function (Blueprint $table) {
            $table->dropColumn('min_wajib');
            $table->dropColumn('max_opsional');
        });
    }
};
