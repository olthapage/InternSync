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
        Schema::create('user_kompetensi', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('kompetensi_id');
            $table->decimal('nilai', 5, 2);

            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('kompetensi_id')->references('kompetensi_id')->on('m_detail_kompetensi');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kompetensi');
    }
};
