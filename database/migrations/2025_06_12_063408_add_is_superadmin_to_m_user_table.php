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
        // Menggunakan Schema::table() untuk mengubah tabel yang sudah ada
        Schema::table('m_user', function (Blueprint $table) {
            // Menambahkan kolom 'is_superadmin'
            // Tipe: boolean (akan menjadi TINYINT(1) di MySQL)
            // Default: 0 (false)
            // After: Ditempatkan setelah kolom 'password' agar rapi
            $table->boolean('is_superadmin')->default(0)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Aksi untuk membatalkan migration (jika diperlukan)
        Schema::table('m_user', function (Blueprint $table) {
            $table->dropColumn('is_superadmin');
        });
    }
};
