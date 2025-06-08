<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidasiAkunTable extends Migration
{
    public function up()
    {
        Schema::create('validasi_akun', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('username')->unique(); // NIM atau NIDN
            $table->string('password');
            $table->enum('perkiraan_role', ['mahasiswa', 'dosen'])->nullable(); // hasil deteksi awal, bisa bantu admin
            $table->enum('status_validasi', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('validasi_akun');
    }
}
