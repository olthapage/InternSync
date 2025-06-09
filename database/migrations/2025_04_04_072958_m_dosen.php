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
        Schema::create('m_dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telepon')->nullable();
            $table->string('foto')->nullable();
            $table->string('nip');
            $table->enum('role_dosen', ['dpa', 'pembimbing'])->default('pembimbing');

            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->timestamps();

            $table->foreign('prodi_id')->references('prodi_id')->on('tabel_prodi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_dosen');
    }
};
