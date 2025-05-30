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
            $table->id('lowongan_skill_id');
            $table->unsignedBigInteger('lowongan_id');
            $table->unsignedBigInteger('skill_id');
            $table->integer('bobot')->default(0);
            $table->enum('level_kompetensi', ['Beginner', 'Intermediate', 'Expert'])->default('Beginner');
            $table->timestamp('created_at')->nullable();

            $table->foreign('lowongan_id')->references('lowongan_id')->on('m_detail_lowongan')->onDelete('cascade');
            $table->foreign('skill_id')->references('skill_id')->on('m_detail_skill')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan_skill');
    }
};
