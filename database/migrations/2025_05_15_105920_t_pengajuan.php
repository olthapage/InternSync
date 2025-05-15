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
        Schema::create('t_pengajuan', function (Blueprint $table) {
            $table->id('pengajuan_id');

            // Foreign keys
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('lowongan_id');

            // Data pendaftaran
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['belum', 'diterima', 'ditolak'])->default('belum');

            $table->timestamps();

            // Relasi foreign key
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
