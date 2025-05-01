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
        Schema::create('lowongan_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('skill_id');

            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_detail_lowongan');
            $table->foreign('skill_id')->references('skill_id')->on('m_detail_skill');
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
