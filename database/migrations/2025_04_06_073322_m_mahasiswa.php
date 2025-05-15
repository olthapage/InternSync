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
            $table->decimal('ipk', 3, 2)->nullable();
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level_user')->onDelete('cascade');;

            $table->foreign('prodi_id')->references('prodi_id')->on('tabel_prodi')->onDelete('cascade');;

            $table->foreign('dosen_id')->references('dosen_id')->on('m_dosen')->onDelete('cascade');

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
