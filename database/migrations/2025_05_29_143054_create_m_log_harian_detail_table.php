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
        Schema::create('m_logharian_detail', function (Blueprint $table) {
            $table->id('logHarianDetail_id');
            $table->unsignedBigInteger('logHarian_id');
            $table->text('isi');
            $table->string('lokasi'); 
            $table->date('tanggal_kegiatan');
            $table->enum('status_approval_dosen', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->enum('status_approval_industri', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_industri')->nullable();
            $table->timestamps();

            $table->foreign('logHarian_id')
                  ->references('logHarian_id')
                  ->on('m_logHarian')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_logharian_detail');
    }
};
