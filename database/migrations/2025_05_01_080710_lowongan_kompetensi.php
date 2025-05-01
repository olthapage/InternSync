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
        Schema::create('lowongan_kompetensi', function (Blueprint $table) {
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('kompetensi_id');

            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_detail_lowongan');
            $table->foreign('kompetensi_id')->references('kompetensi_id')->on('m_detail_kompetensi');
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
