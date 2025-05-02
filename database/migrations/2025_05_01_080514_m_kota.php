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
        Schema::create('m_kota', function (Blueprint $table) {
            $table->id('kota_id');
            $table->string('kota_kode')->unique();
            $table->string('kota_nama');
            $table->unsignedBigInteger('provinsi_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('provinsi_id')->references('provinsi_id')->on('m_provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_kota');
    }
};
