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
        Schema::table('about_us', function (Blueprint $table) {
            // Mengganti nama kolom 'description' menjadi 'content'
            $table->renameColumn('description', 'content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_us', function (Blueprint $table) {
            // Mengembalikan nama kolom jika migrasi di-rollback
            $table->renameColumn('content', 'description');
        });
    }
};
