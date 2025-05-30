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
        Schema::create('m_logHarian', function (Blueprint $table) {
            $table->id('logHarian_id');
            $table->unsignedBigInteger('mahasiswa_magang_id');
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('mahasiswa_magang_id')
                  ->references('mahasiswa_magang_id')
                  ->on('mahasiswa_magang')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_logHarian');
    }
};
