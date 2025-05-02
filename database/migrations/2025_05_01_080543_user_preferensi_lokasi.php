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
        Schema::create('user_preferensi_lokasi', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kota_id');
            $table->integer('prioritas');
            $table->timestamp('created_at')->nullable();

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('kota_id')->references('kota_id')->on('m_kota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferensi_lokasi');
    }
};
