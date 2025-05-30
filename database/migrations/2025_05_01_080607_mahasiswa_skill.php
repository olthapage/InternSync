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
        Schema::create('mahasiswa_skill', function (Blueprint $table) {
            $table->id('mahasiswa_skill_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('skill_id');
            $table->enum('level_kompetensi', ['Beginner', 'Intermediate', 'Expert'])->default('Beginner');
            $table->enum('status_verifikasi', ['Pending', 'Valid', 'Invalid'])->default('Pending');
            $table->timestamp('created_at')->nullable();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
            $table->foreign('skill_id')->references('skill_id')->on('m_detail_skill')->onDelete('cascade');
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
