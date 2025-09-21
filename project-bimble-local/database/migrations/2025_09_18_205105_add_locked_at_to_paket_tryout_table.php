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
            // Menambahkan kolom setelah 'status'. Kolom ini akan mencatat
            // kapan paket pertama kali dikerjakan.
            $table->timestamp('locked_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_tryout', function (Blueprint $table) {
            $table->dropColumn('locked_at');
        });
    }
};
