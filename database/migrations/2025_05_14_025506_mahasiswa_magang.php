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
        Schema::create('mahasiswa_magang', function (Blueprint $table) {
            $table->id('mahasiswa_magang_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->enum('status', ['belum', 'sedang', 'selesai'])->default('belum'); // status magang mahasiswa
            $table->text('evaluasi')->nullable(); //mahasiswa
            $table->text('feedback_dosen')->nullable();
            $table->text('feedback_industri')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_detail_lowongan')->onDelete('cascade');
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
