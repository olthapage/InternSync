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
        Schema::create('lowongan_tipe_kerja', function (Blueprint $table) {
            $table->foreignId('lowongan_id')->constrained('m_detail_lowongan', 'lowongan_id')->onDelete('cascade');
            $table->foreignId('tipe_kerja_id')->constrained('m_tipe_kerja', 'tipe_kerja_id')->onDelete('cascade');
            $table->primary(['lowongan_id', 'tipe_kerja_id']);
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
