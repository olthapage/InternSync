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
        Schema::create('m_detail_skill', function (Blueprint $table) {
            $table->id('skill_id');
            $table->string('skill_nama');
            $table->unsignedBigInteger('kategori_skill_id')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->foreign('kategori_skill_id')->references('kategori_skill_id')->on('m_kategori_skill');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_detail_skill');
    }
};
