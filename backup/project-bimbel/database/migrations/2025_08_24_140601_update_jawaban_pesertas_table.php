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
        // Hapus kolom session_id yang tidak lagi dibutuhkan
        $table->dropColumn('session_id');
        // Tambahkan foreign key ke tabel students
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade')->after('paket_tryout_id');
    });
}

public function down(): void
{
    Schema::table('jawaban_pesertas', function (Blueprint $table) {
        $table->dropForeign(['student_id']);
        $table->dropColumn('student_id');
        $table->string('session_id')->nullable();
    });
}
};
