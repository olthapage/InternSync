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
        Schema::create('lowongan_fasilitas', function (Blueprint $table) {
            $table->foreignId('lowongan_id')->constrained('m_detail_lowongan', 'lowongan_id')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('m_fasilitas', 'fasilitas_id')->onDelete('cascade');
            $table->primary(['lowongan_id', 'fasilitas_id']);
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
