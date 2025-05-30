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
        Schema::create('portofolio_mahasiswa', function (Blueprint $table) {
            $table->id('portofolio_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('judul_portofolio');                                                   // Misal: "Pengembangan Website E-commerce PT. Sejahtera"
            $table->text('deskripsi_portofolio')->nullable();                                     // Penjelasan tentang proyek/portofolio
            $table->enum('tipe_portofolio', ['file', 'url', 'gambar', 'video'])->default('file'); // Jenis portofolio
            $table->string('lokasi_file_atau_url');                                               // Path ke file jika diupload, atau URL jika link
            $table->date('tanggal_pengerjaan_mulai')->nullable();
            $table->date('tanggal_pengerjaan_selesai')->nullable();
            $table->timestamps();

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa')->onDelete('cascade');
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
