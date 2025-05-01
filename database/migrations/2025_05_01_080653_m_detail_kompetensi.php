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
        Schema::create('m_detail_kompetensi', function (Blueprint $table) {
            $table->id('kompetensi_id');
            $table->string('nama_matkul');
            $table->unsignedBigInteger('kategori_kompetensi_id')->nullable();

            $table->foreign('kategori_kompetensi_id')->references('kategori_kompetensi_id')->on('m_kategori_kompetensi');
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
