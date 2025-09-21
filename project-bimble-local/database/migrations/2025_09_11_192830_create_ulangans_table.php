<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('mata_pelajaran')->onDelete('set null');
            $table->string('nama_ulangan');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulangans');
    }
};
