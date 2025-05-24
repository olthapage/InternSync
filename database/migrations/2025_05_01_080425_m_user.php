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
        Schema::create('m_user', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('foto')->nullable();
            $table->string('telepon')->nullable();
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->timestamps();

            $table->foreign('level_id')->references('level_id')->on('m_level_user')->onDelete('cascade');;

            $table->foreign('prodi_id')->references('prodi_id')->on('tabel_prodi')->onDelete('cascade');;
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
