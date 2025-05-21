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
        Schema::create('m_detail_lowongan', function (Blueprint $table) {
            $table->id('lowongan_id');
            $table->string('judul_lowongan');
            $table->text('deskripsi');
            $table->unsignedBigInteger('industri_id')->nullable();
            $table->unsignedBigInteger('kategori_skill_id')->nullable();
            $table->integer('slot')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            $table->timestamp('created_at')->nullable();

            $table->foreign('industri_id')->references('industri_id')->on('m_industri')->onDelete('cascade');
            $table->foreign('kategori_skill_id')->references('kategori_skill_id')->on('m_kategori_skill')->onDelete('cascade');
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
