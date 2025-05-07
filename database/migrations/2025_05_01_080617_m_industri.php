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
        Schema::create('m_industri', function (Blueprint $table) {
            $table->id('industri_id');
            $table->string('industri_nama');
            $table->unsignedBigInteger('kota_id');
            $table->unsignedBigInteger('kategori_industri_id')->nullable();


            $table->foreign('kota_id')->references('kota_id')->on('m_kota')->onDelete('cascade');
            $table->foreign('kategori_industri_id')->references('kategori_industri_id')->on('m_kategori_industri')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_industri');
    }
};
