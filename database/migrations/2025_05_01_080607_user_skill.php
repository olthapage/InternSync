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
        Schema::create('user_skill', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('skill_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');
            $table->foreign('skill_id')->references('skill_id')->on('m_detail_skill');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skill');
    }
};
