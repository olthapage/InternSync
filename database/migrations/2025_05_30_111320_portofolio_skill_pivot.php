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
        Schema::create('portofolio_skill_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('portofolio_id');
            $table->unsignedBigInteger('mahasiswa_skill_id'); // Alternatif: merujuk ke mahasiswa_skill.mahasiswa_skill_id jika ingin mengaitkan dengan klaim level spesifik
            $table->text('deskripsi_penggunaan_skill')->nullable(); // Bagaimana skill ini diterapkan di portofolio tsb

            $table->foreign('portofolio_id')->references('portofolio_id')->on('portofolio_mahasiswa')->onDelete('cascade');
            $table->foreign('mahasiswa_skill_id')->references('mahasiswa_skill_id')->on('mahasiswa_skill')->onDelete('cascade');

            $table->unique(['portofolio_id', 'mahasiswa_skill_id']); // Atau unique berdasarkan mahasiswa_skill_id jika itu yg dipakai
            $table->timestamps(); // Opsional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
