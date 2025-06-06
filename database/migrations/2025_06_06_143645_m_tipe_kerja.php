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
        Schema::create('m_tipe_kerja', function (Blueprint $table) {
            $table->id('tipe_kerja_id');
            $table->string('nama_tipe_kerja', 50)->unique(); // WFH, WFO/On-Site, Hybrid
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
