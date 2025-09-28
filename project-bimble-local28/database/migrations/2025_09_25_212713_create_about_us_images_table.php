<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   Public function up(): void
{
    if (!Schema::hasTable('about_us_images')) {
        Schema::create('about_us_images', function (Blueprint $table) {
            $table->id();
            // --- PERBAIKAN DI SINI ---
            // Secara eksplisit beritahu Laravel nama tabelnya adalah 'about_us'
            $table->foreignId('about_us_id')->constrained('about_us')->onDelete('cascade');
            // --- SELESAI PERBAIKAN ---
            $table->string('image_path');
            $table->timestamps();
        });
    }

    Schema::table('about_us', function (Blueprint $table) {
        if (Schema::hasColumn('about_us', 'image_path')) {
            $table->dropColumn('image_path');
        }
    });
}
    // Fungsi down() sudah aman karena menggunakan dropIfExists
    public function down(): void
    {
        Schema::dropIfExists('about_us_images');

        Schema::table('about_us', function (Blueprint $table) {
            if (!Schema::hasColumn('about_us', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }
};
