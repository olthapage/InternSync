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
        Schema::create('m_mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nim');
            $table->boolean('status')->default(0);
            $table->decimal('ipk', 3, 2);
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level_user');

            $table->foreign('prodi_id')->references('prodi_id')->on('tabel_prodi');

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_mahasiswa');
    }
};
