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
            $table->string('foto')->nullable();
            $table->string('telepon')->nullable();
            $table->string('password');
            $table->string('nim');
            $table->boolean('status')->default(0);
            $table->enum('status_verifikasi', ['pending', 'valid', 'invalid'])->nullable();
            $table->text('alasan')->nullable(); // Alasan jika status_verifikasi invalid
            $table->decimal('ipk', 3, 2)->nullable();
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->unsignedBigInteger('dpa_id')->nullable()->comment('Foreign Key ke m_dosen untuk DPA');
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level_user')->onDelete('cascade');

            $table->foreign('prodi_id')->references('prodi_id')->on('tabel_prodi')->onDelete('cascade');

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen')->onDelete('cascade');

            $table->foreign('dpa_id')->references('dosen_id')->on('m_dosen')->onDelete('cascade');

            $table->string('sertifikat_kompetensi')->nullable();
            $table->string('pakta_integritas')->nullable();
            $table->string('daftar_riwayat_hidup')->nullable();
            $table->string('khs')->nullable();
            $table->string('ktp')->nullable();
            $table->string('ktm')->nullable();
            $table->string('surat_izin_ortu')->nullable();
            $table->string('bpjs')->nullable();
            $table->string('sktm_kip')->nullable();
            $table->string('proposal')->nullable();
            $table->integer('skor_ais')->default(0)->nullable();
            $table->enum('organisasi', ['aktif', 'sangat_aktif', 'tidak_ikut' ])->default('tidak_ikut')->nullable();
            $table->enum('lomba', ['aktif', 'sangat_aktif', 'tidak_ikut' ])->default('tidak_ikut')->nullable();
            $table->enum('kasus', ['ada', 'tidak_ada'])->default('tidak_ada');

            $table->string('sertifikat_organisasi')->nullable();
            $table->string('sertifikat_lomba')->nullable();
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
